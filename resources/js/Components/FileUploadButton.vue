<template>
    <div class="flex justify-center items-center mt-10">
      <div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md hover:scale-105 transition duration-300 ease-in-out">
        <input
          type="file"
          accept=".csv"
          class="hidden"
          ref="fileInput"
          @change="handleFileUpload"
        />
        <button
          :disabled="isUploading"
          @click="$refs.fileInput.click()"
          class="flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400"
        >
          <img
            v-if="!isUploading"
            src="/assets/images/file-upload.svg"
            alt="Upload"
            class="mr-2 -ml-1 h-5 w-5"
          />
          <svg v-if="isUploading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span v-if="!isUploading">Upload your CSV file</span>
          <span v-else>Uploading...</span>
        </button>
      </div>
    </div>
    <div class="flex justify-center items-center mt-10">
      <!-- Message boxes below the upload button container -->
      <div v-if="errorMessage" class="mt-3 p-3 bg-red-100 text-red-700 rounded-lg px-10">
        {{ errorMessage }}
      </div>
      <div v-if="successMessage" class="mt-3 p-3 bg-green-100 text-green-700 rounded-lg px-10">
        {{ successMessage }}
      </div>
    </div>
  </template>
  
  
<script>
  import axios from 'axios';
    
  export default {
    data() {
      return {
        isUploading: false,
        errorMessage: '',
        successMessage: ''
      };
    },
    methods: {
      handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) {
          return;
        }

        this.isUploading = true;
        this.errorMessage = ''; // Reset error message at the beginning of the upload
        this.successMessage = ''; // Reset success message at the beginning of the upload

        let formData = new FormData();
        formData.append('csv_file', file);

        axios.post('/upload-csv', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(response => {
          this.successMessage = response.data.message || 'File uploaded successfully!';
          this.clearMessages();
        })
        .catch(error => {
          this.errorMessage = error.response.data.message || 'An error occurred during file upload.';
          this.clearMessages();
        })
        .finally(() => {
          this.isUploading = false;
          this.$refs.fileInput.value = ''; // Reset the input after processing
        });
      },
      clearMessages() {
        setTimeout(() => {
          this.errorMessage = '';
          this.successMessage = '';
        }, 3000); // Clear messages after 3 seconds
      }

    },
  };
  </script>
  
  <style scoped>
  button:disabled {
    cursor: not-allowed;
  }
</style>
  