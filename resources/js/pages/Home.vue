<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">
          Welcome to Dental Supplies Store
        </h1>

        <!-- Featured Products Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
          <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">
              Featured Products
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Here are the top products for this month.
            </p>
          </div>
          <div class="border-t border-gray-200 px-4 py-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <Link v-for="product in featuredProducts" :key="product.id" :href="`/products/${product.slug}`" class="bg-gray-50 rounded-lg shadow p-4 flex flex-col items-center hover:bg-blue-50 transition">
              <img v-if="product.images && product.images.length" :src="`/storage/${product.images.find(img => img.is_primary)?.image_path || product.images[0].image_path}`" class="w-32 h-32 object-cover rounded mb-4" />
              <div v-else class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-400">No image</div>
              <div class="font-semibold text-lg text-gray-900 mb-2">{{ product.name }}</div>
              <div class="text-blue-600 font-bold text-xl mb-1">${{ formatPrice(product.price) }}</div>
              <div class="text-gray-600 text-sm mb-2">{{ product.brand }}</div>
              <div class="text-gray-500 text-xs text-center">{{ product.description }}</div>
            </Link>
          </div>
        </div>

        <!-- All Products Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">
              All Products
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Browse our complete catalog
            </p>
          </div>
          <div class="border-t border-gray-200 px-4 py-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <Link v-for="product in allProducts" :key="product.id" :href="`/products/${product.slug}`" class="bg-gray-50 rounded-lg shadow p-4 flex flex-col items-center hover:bg-blue-50 transition">
              <img v-if="product.images && product.images.length" :src="`/storage/${product.images.find(img => img.is_primary)?.image_path || product.images[0].image_path}`" class="w-32 h-32 object-cover rounded mb-4" />
              <div v-else class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-400">No image</div>
              <div class="font-semibold text-lg text-gray-900 mb-2">{{ product.name }}</div>
              <div class="text-blue-600 font-bold text-xl mb-1">${{ formatPrice(product.price) }}</div>
              <div class="text-gray-600 text-sm mb-2">{{ product.brand }}</div>
              <div class="text-gray-500 text-xs text-center">{{ product.description }}</div>
            </Link>
          </div>
        </div>

        <!-- Admin Controls - Only show if user is admin -->
        <div v-if="$page.props.auth?.user?.is_admin" class="bg-white shadow overflow-hidden sm:rounded-lg mt-8">
          <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">
              Admin Controls
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Manage your products here.
            </p>
          </div>
          <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:px-6">
              <Link href="/admin/products" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Manage Products
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps(['featuredProducts', 'allProducts']);

function formatPrice(price) {
  const num = parseFloat(price);
  return !isNaN(num) ? num.toFixed(2) : '0.00';
}
</script>
