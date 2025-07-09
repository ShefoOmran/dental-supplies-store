<template>

<div>
    <nav class="bg-white shadow fixed top-0 w-full z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <Link href="/" class="text-xl font-bold text-blue-700">Dental Supplies Store</Link>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <Link href="/" class="border-transparent text-gray-500 hover:border-blue-500 hover:text-blue-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Home
              </Link>
              <Link v-if="$page.props.auth?.user?.is_admin" href="/admin/products" class="border-transparent text-gray-500 hover:border-blue-500 hover:text-blue-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Products
              </Link>
              <Link v-if="$page.props.auth?.user?.is_admin" href="/admin/categories" class="border-transparent text-gray-500 hover:border-blue-500 hover:text-blue-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Categories
              </Link>
            </div>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
            <Link href="/contact-us" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm flex items-center">
              Contact Us
            </Link>
            <Link href="/cart" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm flex items-center">
              <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386a1.5 1.5 0 011.415 1.027L5.91 6.75m0 0h12.239a.75.75 0 01.728.927l-1.5 6A.75.75 0 0116.65 14.25H7.35a.75.75 0 01-.728-.573L4.477 7.5m1.432-.75L6.75 17.25m0 0a1.5 1.5 0 103 0m-3 0a1.5 1.5 0 11-3 0m9 0a1.5 1.5 0 103 0m-3 0a1.5 1.5 0 01-3 0" />
              </svg>
              Cart
            </Link>

            <template v-if="page.props.auth && page.props.auth.user">
              <div class="ml-3 relative">
                <div class="flex items-center space-x-4">
                  <span class="text-gray-700">{{ page.props.auth.user.name }}</span>
                  <form @submit.prevent="logout">
                    <button type="submit" class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 text-sm">
                      Logout
                    </button>
                  </form>
                </div>
              </div>
            </template>
            <template v-else>
              <Link href="/login" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
                Login
              </Link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <main>
      <slot />
    </main>
  </div>

  <div class="min-h-screen bg-gray-100 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-700 px-6 py-4">
          <h1 class="text-2xl font-bold text-white">My Profile</h1>
        </div>
        
        <!-- Profile Content -->
        <div class="px-6 py-8">
    <!-- Success Message from flash -->
    <div v-if="flashSuccess" class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">
      {{ flashSuccess }}
    </div>
    <!-- Success Message from success prop -->
    <div v-if="success" class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">
      {{ success }}
    </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Personal Info -->
            <div>
              <h2 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h2>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-blue-700">Full Name</label>
                  <p class="mt-1 text-sm text-gray-900">{{ user.name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-blue-700">Email</label>
                  <p class="mt-1 text-sm text-gray-900">{{ user.email }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-blue-700">Phone</label>
                  <p class="mt-1 text-sm text-gray-900">{{ user.phone }}</p>
                </div>
              </div>
            </div>
            
            <!-- Update Form -->
            <div>
              <h2 class="text-lg font-medium text-blue-700 mb-4">Update Profile</h2>
              <form @submit.prevent="updateProfile">
                <div class="space-y-4">
                  <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input
                      id="phone"
                      v-model="form.phone"
                      type="tel"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    <p v-if="form.errors.phone" class="mt-2 text-sm text-red-600">{{ form.errors.phone }}</p>
                  </div>
                  
                  <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input
                      id="current_password"
                      v-model="form.current_password"
                      type="password"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    <p v-if="form.errors.current_password" class="mt-2 text-sm text-red-600">{{ form.errors.current_password }}</p>
                  </div>
                  
                  <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input
                      id="password"
                      v-model="form.password"
                      type="password"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</p>
                  </div>
                  
                  <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input
                      id="password_confirmation"
                      v-model="form.password_confirmation"
                      type="password"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                  </div>
                  
                  <div class="pt-2">
                    <button
                      type="submit"
                      class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      :disabled="form.processing"
                    >
                      <span v-if="!form.processing">Save Changes</span>
                      <span v-else class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...                     
                       </span>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue'

const page = usePage()

const success = computed(() => page.props.success ?? '')
const flashSuccess = computed(() => page.props.flash?.success ?? '')

function logout() {
  router.post('/logout', {}, {
    onSuccess: () => {
      router.visit('/login')
    }
  })
}
const props = defineProps({
  user: Object,
});

const form = useForm({
  phone: props.user.phone || '',
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updateProfile = () => {
  form.put('/profile', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset('current_password', 'password', 'password_confirmation')
    }
  })
};
</script>