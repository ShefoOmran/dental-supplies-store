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
          {{ isLoginForm ? 'Login to your account' : 'Sign up for a new account' }}
        </p>
      </div>

      <!-- Login Form -->
      <form v-show="isLoginForm" class="mt-8 space-y-6" @submit.prevent="handleLogin">
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
            <button type="button" class="text-sm text-blue-600 hover:text-blue-500">
              Forgot your password?
            </button>
          </div>
          
          <div class="text-center text-sm text-gray-600">
            Don't have an account? 
            <button @click="toggleForm" type="button" class="text-blue-600 hover:text-blue-500 focus:outline-none">
              Register now
            </button>
          </div>
        </div>
      </form>

      <!-- Signup Form -->
      <form v-show="!isLoginForm" class="mt-8 space-y-6" @submit.prevent="handleSignup">
        <div v-if="signupForm.hasErrors" class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
          <div class="flex">
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">
                There are errors in your submission
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
          <button 
            type="submit" 
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            :disabled="signupForm.processing"
          >
            <span v-if="!signupForm.processing">Sign Up</span>
            <span v-else>
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Processing...
            </span>
          </button>
          
          <div class="text-center text-sm text-gray-600">
            Already have an account? 
            <button @click="toggleForm" type="button" class="text-blue-600 hover:text-blue-500 focus:outline-none">
              Login now
            </button>
          </div>
        </div>
      </form>

      <!-- pop-up error -->
      <div v-if="showError" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
        {{ errorMessage }}
        <button class="ml-4" @click="showError = false">إغلاق</button>
      </div>
      <!-- pop-up success -->
      <div v-if="showSuccess" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
        {{ successMessage }}
        <button class="ml-4" @click="showSuccess = false">إغلاق</button>
      </div>

      <div v-if="$page.props.success ?? false">{{ $page.props.success }}</div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()
const error = computed(() => page.props.error ?? '')
const success = computed(() => page.props.success ?? '')
const errors = computed(() => page.props.errors ?? {})

const isLoginForm = ref(true);
const toggleForm = () => {
  isLoginForm.value = !isLoginForm.value;
};

// Login form
const loginForm = useForm({
  email: '',
  password: '',
  remember: false
});

const showError = ref(false);
const errorMessage = ref('');
const showSuccess = ref(false);
const successMessage = ref('');

// راقب الأخطاء القادمة من السيرفر
watch(
  () => usePage().props.errors,
  (errors) => {
    if (errors && errors.email) {
      errorMessage.value = errors.email;
      showError.value = true;
    }
  },
  { immediate: true }
);

// راقب رسالة النجاح أو الخطأ القادمة من السيرفر
watch(
  () => usePage().props.success,
  (msg) => {
    if (msg) {
      successMessage.value = msg;
      showSuccess.value = true;
    }
  },
  { immediate: true }
);

watch(
  () => usePage().props.error,
  (msg) => {
    if (msg) {
      errorMessage.value = msg;
      showError.value = true;
    }
  },
  { immediate: true }
);

const validateEmail = (email) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
};

const handleLogin = () => {
  loginForm.post('/login', {
    preserveState: true,
    onSuccess: (response) => {
      // استخدم البيانات المرجعة من الخادم للتوجيه
      const redirectPath = response.props.user.is_admin 
        ? '/admin/dashboard' 
        : '/profile';
      
      router.visit(redirectPath);
      
      Swal.fire({
        title: 'Welcome back!',
        text: `You're logged in as ${response.props.user.name}`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      });
    },
    onError: (errors) => {
      Swal.fire({
        title: 'Login Failed',
        text: errors.message || 'Invalid email or password',
        icon: 'error',
        confirmButtonText: 'Try again'
      });
    }
  });
};

// Signup form
const signupForm = useForm({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  errors: {},
  hasErrors: false
});

const handleSignup = () => {
  signupForm.clearErrors();
  
  if (!signupForm.name || !signupForm.email || !signupForm.phone) {
    signupForm.setError('form', 'Please fill all required fields');
    return;
  }

  if (!validateEmail(signupForm.email)) {
    signupForm.setError('email', 'Invalid email address');
    return;
  }

  if (signupForm.password !== signupForm.password_confirmation) {
    signupForm.setError('password_confirmation', 'Passwords do not match');
    return;
  }

  signupForm.post('/register', {
    onSuccess: () => {
      Swal.fire({
        title: 'Success!',
        text: 'Registration successful. Redirecting...',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      }).then(() => {
        signupForm.reset();
        isLoginForm.value = true;
        router.visit('/profile');
      });
    },
    onError: (errors) => {
      if (errors) {
        signupForm.errors = errors;
        signupForm.hasErrors = Object.keys(errors).length > 0;
      }
    }
  });
};

function logout() {
  router.post('/logout', {}, {
    onSuccess: () => {
      router.visit('/login')
    }
  })
}

</script>
