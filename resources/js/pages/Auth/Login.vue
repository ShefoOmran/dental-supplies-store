<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <Link href="/" class="text-4xl font-bold text-blue-700">Dental Supplies Store</Link>
        <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
          Welcome Back
        </h2>
        <p class="mt-2 text-center text-gray-600">
          Login to your account
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="submit">
        <div class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input 
              id="email" 
              v-model="form.email" 
              type="email" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="your@email.com" 
            />
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input 
              id="password" 
              v-model="form.password" 
              type="password" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="••••••••" 
            />
          </div>
        </div>

        <div class="space-y-4">
          <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Login
          </button>
          
          <div class="text-center">
            <Link href="/forgot-password" class="text-sm text-blue-600 hover:text-blue-500">
              Forgot your password?
            </Link>
          </div>
          
          <div class="text-center text-sm text-gray-600">
            Don't have an account? 
            <Link href="/register" class="text-blue-600 hover:text-blue-500">
              Register now
            </Link>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

const submit = () => {
  form.post('/login', {
    onSuccess: () => {
      router.visit('/admin/dashboard');
    },
  });
};
</script>