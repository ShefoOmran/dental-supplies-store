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
                    Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} products
                  </div>
                </div>
              </div>
              <div class="border-t border-gray-200 px-4 py-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <Link v-for="product in paginatedProducts" :key="product.id" :href="`/products/${product.slug}`" class="bg-gray-50 rounded-lg shadow p-4 flex flex-col items-center hover:bg-blue-50 transition">
                  <img v-if="product.images && product.images.length" :src="`/${product.images.find(img => img.is_primary)?.image_path || product.images[0].image_path}`" class="w-32 h-32 object-cover rounded mb-4" />
                  <div v-else class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-400">No image</div>
                  <div class="font-semibold text-lg text-gray-900 mb-2">{{ product.name }}</div>
                  <div class="text-blue-600 font-bold text-xl mb-1">${{ formatPrice(product.price) }}</div>
                  <div class="text-gray-600 text-sm mb-2">{{ product.brand }}</div>
                </Link>
              </div>

              <!-- Pagination Controls -->
              <div class="px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                  <button @click="prevPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                  </button>
                  <button @click="nextPage" :disabled="currentPage === totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                  </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button @click="prevPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                      </button>
                      <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="[page === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', 'relative inline-flex items-center px-4 py-2 border text-sm font-medium']">
                        {{ page }}
                      </button>
                      <button @click="nextPage" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </nav>
                  </div>
                </div>
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

// Pagination state
const currentPage = ref(1);
const productsPerPage = 12;

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

// Pagination logic
const totalPages = computed(() => {
  return Math.ceil(filteredProducts.value.length / productsPerPage);
});

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * productsPerPage;
  const end = start + productsPerPage;
  return filteredProducts.value.slice(start, end);
});

const visiblePages = computed(() => {
  const pages = [];
  const maxVisible = 5;
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
  let end = Math.min(totalPages.value, start + maxVisible - 1);
  
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1);
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  
  return pages;
});

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
}

function goToPage(page) {
  currentPage.value = page;
}

function resetFilters() {
  priceRange.value = { min: null, max: null };
  selectedBrands.value = [];
  selectedCategories.value = [];
  currentPage.value = 1;
}

function formatPrice(price) {
  const num = parseFloat(price);
  return !isNaN(num) ? num.toFixed(2) : '0.00';
}
</script>