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
  </template>
  
  
  <script>
  export default {
    data() {
      return {
        isUploading: false,
      };
    },
    methods: {
      handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) {
          return;
        }
        
        this.isUploading = true;
        // Simulate file upload with a timeout
        setTimeout(() => {
          this.isUploading = false;
          // Reset the input after processing
          this.$refs.fileInput.value = '';
        }, 2000); // 2 seconds delay to mimic file processing
      },
    },
  };
  </script>
  
  <style scoped>
  button:disabled {
    cursor: not-allowed;
  }
  </style>
  