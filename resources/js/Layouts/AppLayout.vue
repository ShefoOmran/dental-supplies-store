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

            <template v-if="$page.props.auth?.user">
              <div class="ml-3 relative">
                <div class="flex items-center space-x-4">
                  <span class="text-gray-700">{{ $page.props.auth.user.name }}</span>
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
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

function logout() {
  router.post('/logout');
}
</script>
