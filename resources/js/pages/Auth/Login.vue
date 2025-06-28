<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <Link href="/" class="text-4xl font-bold text-blue-700">Dental Supplies Store</Link>
        
        <!-- Dynamic Title Based on Form Type -->
        <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
          {{ isLoginForm ? 'Welcome Back' : 'Create New Account' }}
        </h2>
        <p class="mt-2 text-center text-gray-600">
          {{ isLoginForm ? 'Login to your account' : 'Sign up to new account' }}
        </p>
      </div>

      <!-- Login Form -->
      <form v-if="isLoginForm" class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="space-y-4">
          <div>
            <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input 
              id="login-email" 
              v-model="loginForm.email" 
              type="email" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="your@email.com" 
            />
          </div>
          <div>
            <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input 
              id="login-password" 
              v-model="loginForm.password" 
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
            <button @click="toggleForm" type="button" class="text-sm text-blue-600 hover:text-blue-500">
              Forgot your password?
            </button>
          </div>
          
          <div class="text-center text-sm text-gray-600">
            Don't have an account? 
            <button @click="toggleForm" type="button" class="text-blue-600 hover:text-blue-500">
              Register now
            </button>
          </div>
        </div>
      </form>

      <!-- Signup Form -->
      <form v-else class="mt-8 space-y-6" @submit.prevent="handleSignup">
        <div v-if="signupForm.hasErrors" class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
    <div class="flex">
      <div class="ml-3">
        <h3 class="text-sm font-medium text-red-800">
          يوجد خطأ في البيانات المدخلة
        </h3>
        <div class="mt-2 text-sm text-red-700">
          <ul class="list-disc pl-5 space-y-1">
            <li v-for="(error, key) in signupForm.errors" :key="key">
              {{ error }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
        <div class="space-y-4">
          <div>
            <label for="signup-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input 
              id="signup-name" 
              v-model="signupForm.name" 
              type="text" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="Your full name" 
            />
          </div>
          <div>
            <label for="signup-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input 
              id="signup-email" 
              v-model="signupForm.email" 
              type="email" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="your@email.com" 
            />
          </div>
          <div>
            <label for="signup-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input 
              id="signup-phone" 
              v-model="signupForm.phone" 
              type="tel" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="+201234567890" 
            />
          </div>
          <div>
            <label for="signup-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input 
              id="signup-password" 
              v-model="signupForm.password" 
              type="password" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="••••••••" 
            />
          </div>
          <div>
            <label for="signup-password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input 
              id="signup-password-confirm" 
              v-model="signupForm.password_confirmation" 
              type="password" 
              required 
              class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="••••••••" 
            />
          </div>
        </div>

        <div class="space-y-4">
          <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Sign Up
          </button>
          
          <div class="text-center text-sm text-gray-600">
            Already have an account? 
            <button @click="toggleForm" type="button" class="text-blue-600 hover:text-blue-500">
              Login now
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

// Toggle between login and signup forms
const isLoginForm = ref(true);
const toggleForm = () => {
  isLoginForm.value = !isLoginForm.value;
};

// Login form
const loginForm = useForm({
  email: '',
  password: '',
});

const handleLogin = () => {
  loginForm.post('/login', {
    onSuccess: () => {
      router.visit('/admin/dashboard');
    },
  });
};

// Signup form
const signupForm = useForm({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
});

const handleSignup = () => {
  signupForm.clearErrors();
  
  if (signupForm.password !== signupForm.password_confirmation) {
    signupForm.setError('password_confirmation', 'تأكيد كلمة المرور غير متطابق');
    return;
  }

  signupForm.post('/', {
    onSuccess: () => {
    },
    onError: (errors) => {
    }
  });
};
</script>