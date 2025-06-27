<template>
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