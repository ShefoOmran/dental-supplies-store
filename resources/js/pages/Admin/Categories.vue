<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow mb-8">
      <div class="max-w-4xl mx-auto px-4 py-4 flex space-x-6">
        <a href="/admin/dashboard" class="text-gray-700 hover:text-blue-700">Dashboard</a>
        <a href="/admin/products" class="text-gray-700 hover:text-blue-700">Products</a>
        <a href="/admin/categories" class="text-blue-700 font-semibold">Categories</a>
      </div>
    </nav>

    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-6">Category Management</h1>

      <div v-if="$page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ $page.props.flash.success }}
      </div>

      <div v-if="$page.props.errors && Object.keys($page.props.errors).length" class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        <div v-for="(error, key) in $page.props.errors" :key="key">{{ error }}</div>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Categories</h2>
          <button @click="openAddModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Category</button>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="category in categories" :key="category.id">
                <td class="px-6 py-4">{{ category.name }}</td>
                <td class="px-6 py-4">{{ category.slug }}</td>
                <td class="px-6 py-4">{{ category.is_active ? 'Yes' : 'No' }}</td>
                <td class="px-6 py-4">
                  <img :src="category.image ? `/${category.image}` : '/categories/default-category.png'" class="h-10 w-10 object-cover rounded" />
                </td>
                <td class="px-6 py-4">
                  <button @click="openEditModal(category)" class="text-blue-600 hover:underline mr-2">Edit</button>
                  <button @click="deleteCategory(category.id)" class="text-red-600 hover:underline">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">{{ isEdit ? 'Edit Category' : 'Add New Category' }}</h2>
        <form @submit.prevent="saveCategory" enctype="multipart/form-data">
          <div class="mb-4">
            <label class="block text-gray-700">Name</label>
            <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2 mt-1" required />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700">Slug</label>
            <input v-model="form.slug" type="text" class="w-full border rounded px-3 py-2 mt-1" required />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700">Description</label>
            <textarea v-model="form.description" class="w-full border rounded px-3 py-2 mt-1"></textarea>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700">Active</label>
            <select v-model="form.is_active" class="w-full border rounded px-3 py-2 mt-1">
              <option :value="1">Yes</option>
              <option :value="0">No</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700">Image</label>
            <input type="file" @change="handleImage" class="w-full border rounded px-3 py-2 mt-1" />
            <div v-if="form.existingImage" class="mt-2">
              <img :src="`/${form.existingImage}`" class="h-20 w-20 object-cover rounded" />
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
import { router } from '@inertiajs/vue3';
export default {
  props: {
    categories: Array,
  },
  data() {
    return {
      showModal: false,
      isEdit: false,
      editCategoryId: null,
      form: {
        name: '',
        slug: '',
        description: '',
        is_active: 1,
        image: null,
        existingImage: null,
      },
    };
  },
  methods: {
    openAddModal() {
      this.isEdit = false;
      this.editCategoryId = null;
      this.form = {
        name: '',
        slug: '',
        description: '',
        is_active: 1,
        image: null,
        existingImage: null,
      };
      this.showModal = true;
    },
    openEditModal(category) {
      this.isEdit = true;
      this.editCategoryId = category.id;
      this.form = {
        name: category.name,
        slug: category.slug,
        description: category.description,
        is_active: category.is_active ? 1 : 0,
        image: null,
        existingImage: category.image,
      };
      this.showModal = true;
    },
    handleImage(e) {
      this.form.image = e.target.files[0];
    },
    saveCategory() {
      const formData = new FormData();
      formData.append('name', this.form.name);
      formData.append('slug', this.form.slug);
      formData.append('description', this.form.description);
      formData.append('is_active', this.form.is_active);
      if (this.form.image) {
        formData.append('image', this.form.image);
      }

      const url = this.isEdit
        ? `/admin/categories/${this.editCategoryId}`
        : '/admin/categories';

      if (this.isEdit) {
        formData.append('_method', 'PUT');
      }

      router.post(url, formData, {
        onSuccess: () => this.resetForm(),
        forceFormData: true,
        preserveScroll: true,
      });
    },
   deleteCategory(id) {
  if (confirm('Are you sure you want to delete this category?')) {
    router.visit(`/admin/categories/${id}`, {
      method: 'delete',
      preserveScroll: true,
      onSuccess: () => this.resetForm()
    });
  }
}
,
    resetForm() {
      this.showModal = false;
      this.isEdit = false;
      this.editCategoryId = null;
      this.form = {
        name: '',
        slug: '',
        description: '',
        is_active: 1,
        image: null,
        existingImage: null,
      };
    },
  },
};
</script>
