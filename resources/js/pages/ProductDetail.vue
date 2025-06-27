<template>
  <div>
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <a href="/" class="text-xl font-bold text-blue-700">Dental Supplies Store</a>
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
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
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
              <a href="/login" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
                Login
              </a>
            </template>
          </div>
        </div>
      </div>
    </nav>
  </div>
  
  <div class="min-h-screen bg-gray-100">
    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-col md:flex-row gap-8">
          <div class="flex-1 flex flex-col items-center">
            <div class="grid grid-cols-3 gap-2 mb-4">
              <img v-for="(img, idx) in product.images" :key="idx" :src="`/${img.image_path}`" class="w-24 h-24 object-cover rounded cursor-pointer border-2 border-transparent hover:border-blue-500" @click="openLightbox(idx)" />
            </div>
            <img :src="`/${product.images[lightboxIndex]?.image_path || product.images[0]?.image_path}`" class="w-64 h-64 object-cover rounded shadow mb-4" v-if="product.images && product.images.length" />
            <div v-else class="w-64 h-64 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-400">No image</div>
          </div>
          <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ product.name }}</h1>
            <div class="text-blue-600 font-bold text-xl mb-1">${{ formatPrice(product.price) }}</div>
            <div class="text-gray-600 text-sm mb-2">{{ product.brand }}</div>
            <div class="text-gray-500 text-base mb-4">{{ product.description }}</div>
            <div class="text-sm text-gray-400">Stock: {{ product.stock }}</div>
          </div>
        </div>
      </div>
    </div>
    <!-- Lightbox Modal -->
    <div v-if="showLightbox" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">
      <img :src="`/${product.images[lightboxIndex]?.image_path}`" class="w-2/3 max-w-2xl rounded shadow-lg" />
      <button @click="showLightbox = false" class="absolute top-8 right-8 text-white text-3xl">&times;</button>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { ref, computed } from 'vue';

export default {
  name: 'ProductDetail',
  props: {
    product: Object
  },
  data() {
    return {
      showLightbox: false,
      lightboxIndex: 0
    }
  },
  methods: {
    formatPrice(price) {
      return Number(price || 0).toFixed(2);
    },
    openLightbox(idx) {
      this.lightboxIndex = idx;
      this.showLightbox = true;
    }
  }
}
</script> 