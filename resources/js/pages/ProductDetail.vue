<template>
  <div>
    <nav class="bg-white shadow">
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
              <Link href="/login" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
                Login
              </Link>
            </template>
          </div>
        </div>
      </div>
    </nav>
  
    <div class="min-h-screen bg-gray-100">
      <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-8">
          <div class="flex flex-col md:flex-row gap-8">
            <!-- Product Images -->
            <div class="flex-1 flex flex-col items-center">
              <div class="grid grid-cols-3 gap-2 mb-6">
                <img 
                  v-for="(img, idx) in product.images" 
                  :key="idx" 
                  :src="`/${img.image_path}`" 
                  class="w-24 h-24 object-cover rounded cursor-pointer border-2 border-transparent hover:border-blue-500" 
                  @click="openLightbox(idx)" 
                />
              </div>
              <img 
                :src="`/${product.images[lightboxIndex]?.image_path || product.images[0]?.image_path}`" 
                class="w-full max-w-md h-auto object-cover rounded-lg shadow-lg mb-6" 
                v-if="product.images && product.images.length" 
              />
              <div v-else class="w-64 h-64 bg-gray-200 flex items-center justify-center rounded mb-6 text-gray-400">
                No image
              </div>
            </div>
            
            <!-- Product Details -->
            <div class="flex-1">
              <!-- Product Name -->
              <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ product.name }}</h1>
              
              <!-- Brand -->
              <div class="text-gray-600 text-lg mb-4">{{ product.brand }}</div>
              
              <!-- Price -->
              <div class="flex items-center mb-6">
                <span class="text-blue-600 font-bold text-3xl mr-4">${{ formatPrice(product.price) }}</span>
                <span v-if="product.original_price" class="text-gray-400 line-through text-xl">${{ formatPrice(product.original_price) }}</span>
              </div>
              
              <!-- Description -->
              <div class="text-gray-700 text-base mb-6">
                {{ product.description }}
              </div>
              
              <!-- Stock -->
              <div class="text-green-600 font-medium text-lg mb-6">
                In Stock ({{ product.stock }} units left)
              </div>
              
              <!-- Quantity Selector -->
              <div class="flex items-center mb-6">
                <label class="text-gray-700 mr-2">Quantity:</label>
                <input 
                  type="number" 
                  v-model="quantity" 
                  min="1" 
                  :max="product.stock" 
                  class="w-24 border border-gray-300 rounded px-3 py-2 text-center mr-2"
                >
                <button 
                  @click="addToCart" 
                  class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition text-lg"
                >
                  Add to Cart
                </button>
              </div>
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
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  product: Object,
  featuredProducts: Array,
  allProducts: Array,
  categories: Array
});

// Lightbox state
const showLightbox = ref(false);
const lightboxIndex = ref(0);
const quantity = ref(1);

// Methods
const formatPrice = (price) => {
  return Number(price || 0).toFixed(2);
};

const openLightbox = (idx) => {
  lightboxIndex.value = idx;
  showLightbox.value = true;
};

const addToCart = () => {
  console.log(`Added ${quantity.value} of ${props.product.name} to cart`);
};
</script>