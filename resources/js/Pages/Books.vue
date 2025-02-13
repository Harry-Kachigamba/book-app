<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Books Management
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <button @click="showCreateModal = true" class="mb-4 bg-blue-500 text-white px-4 py-2 rounded">
                            Add New Book
                        </button>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Published Year</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                                    <th class="px-6 py-3 bg-gray-50"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="book in books" :key="book.id">
                                    <td class="px-6 py-4 whitespace-no-wrap">{{ book.title }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap">{{ book.author }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap">{{ book.published_year }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap">{{ book.genre }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm font-medium">
                                        <button @click="editBook(book)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button @click="deleteBook(book.id)" class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Create/Edit Modal -->
                        <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg w-1/2">
                                <h3 class="text-lg font-medium mb-4">{{ isEditing ? 'Edit Book' : 'Add New Book' }}</h3>
                                <form @submit.prevent="isEditing ? updateBook() : createBook()">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                                        <input v-model="form.title" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Author</label>
                                        <input v-model="form.author" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Published Year</label>
                                        <input v-model="form.published_year" type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Genre</label>
                                        <input v-model="form.genre" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" @click="closeModal" class="mr-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ isEditing ? 'Update' : 'Create' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const books = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const isEditing = ref(false);
const form = ref({
    id: null,
    title: '',
    author: '',
    published_year: '',
    genre: ''
});

onMounted(() => {
    fetchBooks();
});

const fetchBooks = async () => {
    const response = await axios.get('/api/books');
    books.value = response.data;
};

const createBook = async () => {
    await axios.post('/api/books', form.value);
    closeModal();
    await fetchBooks();
};

const editBook = (book) => {
    form.value = { ...book };
    isEditing.value = true;
    showEditModal.value = true;
};

const updateBook = async () => {
    await axios.put(`/api/books/${form.value.id}`, form.value);
    closeModal();
    await fetchBooks();
};

const deleteBook = async (id) => {
    if (confirm('Are you sure you want to delete this book?')) {
        await axios.delete(`/api/books/${id}`);
        await fetchBooks();
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    isEditing.value = false;
    form.value = {
        id: null,
        title: '',
        author: '',
        published_year: '',
        genre: ''
    };
};
</script>
