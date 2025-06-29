<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Hero Section -->
      <div class="bg-gradient-to-r from-blue-500 to-blue-700 py-24 px-4 sm:px-6 lg:px-8 text-white">
        <div class="max-w-7xl mx-auto text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-6 animate-fade-in-down">
            Welcome to Dental Supplies Store
          </h1>
          <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto animate-fade-in-down delay-100">
            High quality dental products and tools at competitive prices
          </p>
          <div class="relative max-w-md mx-auto animate-fade-in-down delay-200">
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Search for products..." 
              class="w-full rounded-full py-3 px-5 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-lg"
              @input="applyFilters"
            >
            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Categories Section -->
        <div class="mb-16 animate-fade-in">
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">
              <span class="border-b-4 border-blue-500 pb-2">Our Main Categories</span>
            </h2>
          </div>
          
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-5 gap-6">
            <Link
              v-for="category in categories"
              :key="category.id"
              class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1 flex flex-col items-center p-4 border border-gray-100"
            >
              <div class="relative w-full h-40 mb-4 overflow-hidden rounded-lg bg-gray-100">
                <img 
                  :src="category.image ? `/${category.image}` : '/categories/default-category.png'" 
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                  :alt="category.name"
                />
              </div>
              <h3 class="text-lg font-semibold text-blue-700 text-center">{{ category.name }}</h3>
              <p class="text-sm text-gray-600 text-center mt-1 line-clamp-2">{{ category.description }}</p>
            </Link>
          </div>
        </div>

          <!-- Featured Products Section -->
  <div class="mb-16 animate-fade-in">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-2xl font-bold text-gray-900">
        <span class="border-b-4 border-blue-500 pb-2">Featured Products</span>
      </h2>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div 
        v-for="product in featuredProducts" 
        :key="product.id" 
        class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1"
      >
        <Link :href="`/products/${product.slug}`" class="block h-full flex flex-col">
          <div class="relative flex-1 h-48 overflow-hidden bg-gray-100">
            <img 
              v-if="product.images && product.images.length" 
              :src="`${product.images.find(img => img.is_primary)?.image_path || product.images[0].image_path}`" 
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              :alt="product.name"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <!-- Quick View Button -->
            <button 
              @click.prevent="openQuickView(product)"
              class="absolute bottom-2 right-2 bg-white/90 hover:bg-blue-600 text-blue-600 hover:text-white rounded-full p-2 shadow-md transition-all duration-300 opacity-0 group-hover:opacity-100"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          <div class="p-4 flex-1 flex flex-col">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="font-semibold text-lg text-blue-900 mb-1 line-clamp-1">{{ product.name }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ product.brand }}</p>
              </div>
              <div class="text-blue-600 font-bold text-xl">${{ formatPrice(product.price) }}</div>
            </div>
            <!-- Rating Stars -->
            <div class="mt-auto">
              <div class="flex items-center">
                <div class="flex">
                  <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="i <= product.rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-gray-300'" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </Link>
      </div>
    </div>
  </div>

        <!-- All Products Section with Filters -->
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Filters Sidebar -->
          <div class="w-full lg:w-80 bg-white p-6 rounded-xl shadow-lg sticky top-24 self-start border border-gray-100">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
              <h2 class="text-xl font-bold text-gray-900">Filters</h2>
              <button @click="resetFilters" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                Reset All
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
              </button>
            </div>
            
            <!-- Search Box -->
            <div class="mb-6">
              <h3 class="font-medium text-base mb-3 text-gray-900">Search Products</h3>
              <div class="relative">
                <input 
                  type="text" 
                  v-model="searchQuery" 
                  placeholder="Search dental products..." 
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                  @input="applyFilters"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </div>
            
            <!-- Price Range Filter -->
            <div class="mb-6">
              <h3 class="font-medium text-base mb-3 text-gray-900">Price Range</h3>
              <div class="flex items-center justify-between gap-3">
                <input 
                  type="number" 
                  v-model="priceRange.min" 
                  placeholder="Min"
                  class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
                <span class="text-gray-400">-</span>
                <input 
                  type="number" 
                  v-model="priceRange.max" 
                  placeholder="Max"
                  class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
              </div>
            </div>
            
            <!-- Brand Filter -->
            <div class="mb-6">
              <h3 class="font-medium text-base mb-3 text-gray-900">Brand</h3>
              <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                <div v-for="brand in availableBrands" :key="brand" class="flex items-center">
                  <input 
                    type="checkbox" 
                    :id="`brand-${brand}`" 
                    :value="brand" 
                    v-model="selectedBrands"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition"
                  >
                  <label :for="`brand-${brand}`" class="ml-2 text-sm text-gray-700">{{ brand }}</label>
                </div>
              </div>
            </div>
            
            <!-- Category Filter -->
            <div class="mb-6">
              <h3 class="font-medium text-base mb-3 text-gray-900">Category</h3>
              <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                <div v-for="category in availableCategories" :key="category.id" class="flex items-center">
                  <input 
                    type="checkbox" 
                    :id="`category-${category.id}`" 
                    :value="category.id" 
                    v-model="selectedCategories"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition"
                  >
                  <label :for="`category-${category.id}`" class="ml-2 text-sm text-gray-700">{{ category.name }}</label>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Products Grid -->
          <div class="flex-1">
            <div class="bg-white shadow overflow-hidden rounded-xl border border-gray-100">
              <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                  <div>
                    <h2 class="text-xl font-bold text-gray-900">
                      All Products
                    </h2>
                    <p class="text-sm text-gray-500">
                      Browse our complete collection
                    </p>
                  </div>
                  <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-500">
                      Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} products
                    </div>
                    <div class="relative">
                      <select 
                        v-model="sortOption"
                        @change="applySorting"
                        class="appearance-none bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      >
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="name_asc">Name: A-Z</option>
                        <option value="name_desc">Name: Z-A</option>
                        <option value="rating_desc">Top Rated</option>
                      </select>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="filteredProducts.length > 0" class="px-4 py-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                <div 
                  v-for="product in paginatedProducts" 
                  :key="product.id" 
                  class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1"
                >
                  <Link :href="`/products/${product.slug}`" class="block">
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                      <img 
                        v-if="product.images && product.images.length" 
                        :src="`/${product.images.find(img => img.is_primary)?.image_path || product.images[0].image_path}`" 
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        :alt="product.name"
                      />
                      <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <!-- Quick View Button -->
                      <button 
                        @click.prevent="openQuickView(product)"
                        class="absolute bottom-2 right-2 bg-white/90 hover:bg-blue-600 text-blue-600 hover:text-white rounded-full p-2 shadow-md transition-all duration-300 opacity-0 group-hover:opacity-100"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                    </div>
                    </Link>
                    <div class="p-4">
                      <div class="flex justify-between items-start">
                        <div>
                          <h3 class="font-semibold text-lg text-blue-600 mb-1 line-clamp-1">{{ product.name }}</h3>
                          <p class="text-gray-600 text-sm mb-2">{{ product.brand }}</p>
                        </div>
                        <div class="text-blue-600 font-bold text-xl">${{ formatPrice(product.price) }}</div>
                      </div>
                    <div class="flex items-center mt-2">
                      <div class="flex" @mouseleave="resetRating">
                        <svg  
                          v-for="i in 5" 
                          :key="i" 
                          xmlns="http://www.w3.org/2000/svg" 
                          class="h-5 w-5 cursor-pointer star-icon" 
                          :class="{
                            'text-yellow-400 fill-yellow-400': i <= (ratingHover || product.rating),
                            'text-gray-300 fill-gray-300': i > (ratingHover || product.rating)
                          }" 
                          viewBox="0 0 20 20"
                          @click="setRating(product, i)"
                          @mouseover="hoverRating(i)"
                        >
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </div>
                    </div>
                    </div>
                </div>
              </div>
              <div v-else class="px-4 py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                <button @click="resetFilters" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  Reset Filters
                </button>
              </div>

              <!-- Pagination Controls -->
              <div class="px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                  <button 
                    @click="prevPage" 
                    :disabled="currentPage === 1" 
                    :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-gray-50': currentPage !== 1}"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white"
                  >
                    Previous
                  </button>
                  <button 
                    @click="nextPage" 
                    :disabled="currentPage === totalPages" 
                    :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages, 'hover:bg-gray-50': currentPage !== totalPages}"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white"
                  >
                    Next
                  </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button 
                        @click="prevPage" 
                        :disabled="currentPage === 1" 
                        :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-gray-50': currentPage !== 1}"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                      >
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                      </button>
                      <button 
                        v-for="page in visiblePages" 
                        :key="page" 
                        @click="goToPage(page)" 
                        :class="[page === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600 z-10' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', 'relative inline-flex items-center px-4 py-2 border text-sm font-medium']"
                      >
                        {{ page }}
                      </button>
                      <button 
                        @click="nextPage" 
                        :disabled="currentPage === totalPages" 
                        :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages, 'hover:bg-gray-50': currentPage !== totalPages}"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                      >
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

        <!-- Quick View Modal -->
        <div v-if="quickViewProduct" class="fixed inset-0 overflow-y-auto z-50">
          <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
              <div class="absolute inset-0 bg-gray-500 opacity-75" @click="closeQuickView"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <button @click="closeQuickView" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                  <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <div class="flex flex-col md:flex-row gap-6">
                      <div class="w-full md:w-1/2">
                        <div class="h-64 md:h-80 bg-gray-100 rounded-lg overflow-hidden">
                          <img 
                            v-if="quickViewProduct.images && quickViewProduct.images.length" 
                            :src="`/${quickViewProduct.images.find(img => img.is_primary)?.image_path || quickViewProduct.images[0].image_path}`" 
                            class="w-full h-full object-cover"
                            :alt="quickViewProduct.name"
                          />
                          <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                          </div>
                        </div>
                      </div>
                      <div class="w-full md:w-1/2">
                        <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-2">
                          {{ quickViewProduct.name }}
                        </h3>
                        <div class="flex items-center mb-4">
                          <div class="flex">
                            <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="i <= quickViewProduct.rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-gray-300'" viewBox="0 0 20 20">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                          </div>
                        </div>
                        <div class="text-3xl font-bold text-blue-600 mb-4">${{ formatPrice(quickViewProduct.price) }}</div>
                        <div class="mb-4">
                          <span class="text-gray-900 font-medium">Brand:</span>
                          <span class="text-gray-700 ml-2">{{ quickViewProduct.brand }}</span>
                        </div>
                        <div class="mb-4">
                          <span class="text-gray-900 font-medium">Category:</span>
                          <span class="text-gray-700 ml-2">{{ getCategoryName(quickViewProduct.category_id) }}</span>
                        </div>
                        <div class="mt-6 flex gap-3">
                          <Link 
                            :href="`/products/${quickViewProduct.slug}`" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition duration-200 text-center"
                          >
                            View Full Details
                          </Link>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Admin Controls -->
        <div v-if="$page.props.auth?.user?.is_admin" class="bg-white shadow overflow-hidden sm:rounded-xl mt-8 border border-gray-100 animate-fade-in">
          <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg leading-6 font-medium text-gray-900">
              Admin Controls
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Manage your store content
            </p>
          </div>
          <div class="px-6 py-4 flex flex-wrap gap-4">
            <Link href="/admin/products" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Manage Products
            </Link>
            <Link href="/admin/categories" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
              Manage Categories
            </Link>
            <Link href="/admin/orders" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
              View Orders
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { ref, computed, watch } from 'vue';

const props = defineProps({
  featuredProducts: Array,
  allProducts: Array,
  categories: Array
});

// Search and Filter state
const searchQuery = ref('');
const priceRange = ref({
  min: null,
  max: null
});
const selectedBrands = ref([]);
const selectedCategories = ref([]);
const sortOption = ref('price_asc');

// Pagination state
const currentPage = ref(1);
const productsPerPage = 12;

// Quick View state
const quickViewProduct = ref(null);

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

// Apply sorting to products
const sortedProducts = computed(() => {
  let products = [...props.allProducts];
  
  switch(sortOption.value) {
    case 'price_asc':
      return products.sort((a, b) => a.price - b.price);
    case 'price_desc':
      return products.sort((a, b) => b.price - a.price);
    case 'name_asc':
      return products.sort((a, b) => a.name.localeCompare(b.name));
    case 'name_desc':
      return products.sort((a, b) => b.name.localeCompare(a.name));
    case 'rating_desc':
      return products.sort((a, b) => {
        const ratingA = a.rating || 0;
        const ratingB = b.rating || 0;
        return ratingB - ratingA;
      });
    default:
      return products;
  }
});

// Filtered products with search and filters
const filteredProducts = computed(() => {
  return sortedProducts.value.filter(product => {
    // Search filter
    if (searchQuery.value && !product.name.toLowerCase().includes(searchQuery.value.toLowerCase())) {
      return false;
    }
    
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

function applyFilters() {
  currentPage.value = 1; // Reset to first page when filters change
}

function applySorting() {
  currentPage.value = 1;
}

function resetFilters() {
  searchQuery.value = '';
  priceRange.value = { min: null, max: null };
  selectedBrands.value = [];
  selectedCategories.value = [];
  sortOption.value = 'price_asc';
  currentPage.value = 1;
}

function formatPrice(price) {
  const num = parseFloat(price);
  return !isNaN(num) ? num.toFixed(2) : '0.00';
}

function openQuickView(product) {
  quickViewProduct.value = product;
}

function closeQuickView() {
  quickViewProduct.value = null;
}

function getCategoryName(categoryId) {
  const category = props.categories.find(c => c.id === categoryId);
  return category ? category.name : 'Uncategorized';
}

// Watch for filter changes and apply them
watch([searchQuery, priceRange, selectedBrands, selectedCategories], () => {
  applyFilters();
}, { deep: true });


const ratingHover = ref(0);
const ratingSelected = ref(0);

function setRating(product, rating) {
  product.rating = rating;
  product.review_count += 1;
  ratingSelected.value = rating;
}

function hoverRating(rating) {
  ratingHover.value = rating;
}

function resetRating() {
  ratingHover.value = 0;
}

</script>

<style>
/* Animation classes */
.animate-fade-in-down {
  animation: fadeInDown 0.6s ease-out forwards;
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out forwards;
}

.delay-100 {
  animation-delay: 0.1s;
}

.delay-200 {
  animation-delay: 0.2s;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Custom scrollbar for filter sections */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}

/* Line clamp for text overflow */
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.star-filled {
  fill: #f59e0b;
  color: #f59e0b;
}

.star-empty {
  fill: #d1d5db;
  color: #d1d5db;
}

.product-card {
  width: 400px;
}

.star-icon {
  transition: all 0.2s ease;
}
.star-icon:hover {
  transform: scale(1.2);
}
</style>