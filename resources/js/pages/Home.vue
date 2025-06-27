<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-100">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">
          <center>Welcome to Dental Supplies Store</center>
        </h1>

        <!-- Categories Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
          <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">
              Our Main Categories
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Here are our categories.
            </p>
          </div>
          <div class="border-t border-gray-200 px-4 py-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <Link
              v-for="category in categories"
              :key="category.id"
              class="bg-white p-4 rounded shadow hover:bg-blue-100 transition flex flex-col items-center"
            >
              <img :src="category.image ? `/${category.image}` : '/categories/default-category.png'" class="h-20 w-20 object-cover rounded" />
              <div class="text-lg font-bold text-center">{{ category.name }}</div>
              <p class="text-sm text-gray-600 text-center">{{ category.description }}</p>
            </Link>
          </div>
        </div>

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
            </Link>
          </div>
        </div>

        <!-- All Products Section with Filters -->
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Filters Sidebar -->
          <div class="w-full lg:w-80 bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
              <h2 class="text-xl font-bold">Filters</h2>
              <button @click="resetFilters" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Reset All
              </button>
            </div>
            
            <!-- Price Range Filter -->
            <div class="mb-6">
              <h3 class="font-medium text-base mb-3">Price Range</h3>
              <div class="flex items-center justify-between gap-3">
                <input type="number" v-model="priceRange.min" placeholder="Min"
                       class="w-28 border border-gray-300 rounded px-3 py-2 text-sm">
                <span class="text-gray-400">-</span>
                <input type="number" v-model="priceRange.max" placeholder="Max"
                       class="w-28 border border-gray-300 rounded px-3 py-2 text-sm">
              </div>
            </div>
            
            <!-- Brand Filter -->
            <div class="mb-6">
              <h3 class="font-medium text-base mb-3">Brand</h3>
              <div class="space-y-2">
                <div v-for="brand in availableBrands" :key="brand" class="flex items-center">
                  <input type="checkbox" :id="`brand-${brand}`" :value="brand" v-model="selectedBrands" 
                         class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                  <label :for="`brand-${brand}`" class="ml-2 text-sm text-gray-700">{{ brand }}</label>
                </div>
              </div>
            </div>
            
            <!-- Category Filter -->
            <div class="mb-6">
              <h3 class="font-medium text-base mb-3">Category</h3>
              <div class="space-y-2">
                <div v-for="category in availableCategories" :key="category.id" class="flex items-center">
                  <input type="checkbox" :id="`category-${category.id}`" :value="category.id" v-model="selectedCategories" 
                         class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                  <label :for="`category-${category.id}`" class="ml-2 text-sm text-gray-700">{{ category.name }}</label>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Products Grid -->
          <div class="flex-1">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
              <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                  <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">
                      All Products
                    </h2>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                      Browse our Products
                    </p>
                  </div>
                  <div class="text-sm text-gray-500">
                    Showing {{ filteredProducts.length }} products
                  </div>
                </div>
              </div>
              <div class="border-t border-gray-200 px-4 py-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <Link v-for="product in filteredProducts" :key="product.id" :href="`/products/${product.slug}`" class="bg-gray-50 rounded-lg shadow p-4 flex flex-col items-center hover:bg-blue-50 transition">
                  <img v-if="product.images && product.images.length" :src="`/${product.images.find(img => img.is_primary)?.image_path || product.images[0].image_path}`" class="w-32 h-32 object-cover rounded mb-4" />
                  <div v-else class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-400">No image</div>
                  <div class="font-semibold text-lg text-gray-900 mb-2">{{ product.name }}</div>
                  <div class="text-blue-600 font-bold text-xl mb-1">${{ formatPrice(product.price) }}</div>
                  <div class="text-gray-600 text-sm mb-2">{{ product.brand }}</div>
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Admin Controls -->
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
import { ref, computed } from 'vue';

const props = defineProps(['featuredProducts', 'allProducts', 'categories']);

// Filter state
const priceRange = ref({
  min: null,
  max: null
});
const selectedBrands = ref([]);
const selectedCategories = ref([]);

// Extract available brands and categories from products
const availableBrands = computed(() => {
  const brands = new Set();
  props.allProducts.forEach(product => {
    if (product.brand) {
      brands.add(product.brand);
    }
  });
  return Array.from(brands).sort();
});

const availableCategories = computed(() => {
  return props.categories;
});

// Filtered products with category_id filtering
const filteredProducts = computed(() => {
  return props.allProducts.filter(product => {
    // Price range filter
    if (priceRange.value.min !== null && product.price < priceRange.value.min) {
      return false;
    }
    if (priceRange.value.max !== null && product.price > priceRange.value.max) {
      return false;
    }
    
    // Brand filter
    if (selectedBrands.value.length > 0 && !selectedBrands.value.includes(product.brand)) {
      return false;
    }
    
    // Category filter using category_id
    if (selectedCategories.value.length > 0 && !selectedCategories.value.includes(product.category_id)) {
      return false;
    }
    
    return true;
  });
});

function applyFilters() {
  // The computed property will automatically update
}

function resetFilters() {
  priceRange.value = { min: null, max: null };
  selectedBrands.value = [];
  selectedCategories.value = [];
}

function formatPrice(price) {
  const num = parseFloat(price);
  return !isNaN(num) ? num.toFixed(2) : '0.00';
}
</script>