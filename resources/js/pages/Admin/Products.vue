<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-gray-900">Admin Dashboard</h1>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <a href="/admin/dashboard" class="cursor-pointer border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Dashboard
              </a>
              <a href="/admin/categories" class="cursor-pointer border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Categories
              </a>
              <a href="/admin/products" class="cursor-pointer border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Products
              </a>
            </div>
          </div>
          <div class="flex items-center">
            <form @submit.prevent="logout" method="POST">
              <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </nav>
    <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-6">Product Management</h1>
      <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.errors && Object.keys($page.props.errors).length" class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        <div v-for="(error, key) in $page.props.errors" :key="key">{{ error }}</div>
      </div>
      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Products</h2>
          <button @click="openAddModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Product</button>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="product in products.data" :key="product.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ product.category?.name || 'Uncategorized' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">${{ formatPrice(product.price) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ product.stock }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
  v-if="product.images && product.images.length"
  :src="`${$page.props.asset}/${product.images.find(img => img.is_primary)?.image_path || product.images[0].image_path}`"
  class="w-12 h-12 object-cover rounded"
/>
                  <span v-else class="text-gray-400">No image</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button @click="openEditModal(product)" class="text-blue-600 hover:underline mr-2">Edit</button>
                  <button @click="deleteProduct(product.id)" class="text-red-600 hover:underline">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add Product Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <h2 class="text-xl font-bold mb-4">{{ isEdit ? 'Edit Product' : 'Add New Product' }}</h2>
        <form @submit.prevent="addProduct">
          <div class="mb-4">
            <label class="block text-gray-700">Name</label>
            <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2 mt-1" required />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Category</label>
            <select v-model="form.category_id" class="w-full border rounded px-3 py-2 mt-1" required>
              <option value="">Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Price</label>
            <input v-model="form.price" type="number" step="0.01" class="w-full border rounded px-3 py-2 mt-1" required />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Stock</label>
            <input v-model="form.stock" type="number" class="w-full border rounded px-3 py-2 mt-1" required />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">SKU</label>
            <input v-model="form.sku" type="text" class="w-full border rounded px-3 py-2 mt-1" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Brand</label>
            <input v-model="form.brand" type="text" class="w-full border rounded px-3 py-2 mt-1" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Description</label>
            <textarea v-model="form.description" class="w-full border rounded px-3 py-2 mt-1"></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Images</label>
            <input type="file" multiple @change="handleImageChange" class="w-full border rounded px-3 py-2 mt-1" />
            <div class="flex flex-wrap mt-2">
              <img v-for="(img, idx) in imagePreviews" :key="idx" :src="img" class="w-16 h-16 object-cover rounded mr-2 mb-2" />
            </div>
          </div>
          <div class="flex justify-end">
            <button type="button" @click="showModal = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/vue3';

function logout() {
  router.post('/logout');
}
export default {
  name: 'AdminProducts',
  props: {
    products: Array,
    categories: Array,
    overview: {
      type: Object,
      default: () => ({
        total: 0,
        active: 0,
        withProducts: 0,
        lastUpdated: 'N/A'
      })
    }
  },
  data() {
    return {
      showModal: false,
      isEdit: false,
      editProductId: null,
      form: {
        name: '',
        category_id: '',
        price: '',
        stock: '',
        sku: '',
        brand: '',
        description: ''
      },
      images: [],
      imagePreviews: []
    }
  },
  methods: {
    formatPrice(price) {
      const num = parseFloat(price);
      return !isNaN(num) ? num.toFixed(2) : '0.00';
    },
    handleImageChange(e) {
      this.images = Array.from(e.target.files);
      this.imagePreviews = this.images.map(file => URL.createObjectURL(file));
    },
    addProduct() {
      const formData = new FormData();
      Object.entries(this.form).forEach(([key, value]) => formData.append(key, value));
      this.images.forEach((file, idx) => formData.append('images[]', file));
      if (this.isEdit) {
        formData.append('_method', 'PUT');
        router.post(`/admin/products/${this.editProductId}`, formData, {
          forceFormData: true,
          onSuccess: () => {
            this.resetForm();
          }
        });
      } else {
        router.post('/admin/products', formData, {
          forceFormData: true,
          onSuccess: () => {
            this.resetForm();
          }
        });
      }
    },
    openEditModal(product) {
      this.isEdit = true;
      this.editProductId = product.id;
      this.form = {
        name: product.name,
        category_id: product.category_id,
        price: product.price,
        stock: product.stock,
        sku: product.sku,
        brand: product.brand,
        description: product.description
      };
      this.showModal = true;
    },
    openAddModal() {
      this.isEdit = false;
      this.editProductId = null;
      this.form = {
        name: '',
        category_id: '',
        price: '',
        stock: '',
        sku: '',
        brand: '',
        description: ''
      };
      this.showModal = true;
    },
    deleteProduct(id) {
      if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/admin/products/${id}`);
      }
    },
    resetForm() {
      this.showModal = false;
      this.isEdit = false;
      this.editProductId = null;
      this.form = {
        name: '',
        category_id: '',
        price: '',
        stock: '',
        sku: '',
        brand: '',
        description: ''
      };
      this.images = [];
      this.imagePreviews = [];
    },
    getImageUrl(path) {
      const cleanPath = path.replace(/^storage\//, '');
      return `/storage/${cleanPath}`;
    }
  }
}
</script> 