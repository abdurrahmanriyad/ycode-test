<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <h1 class="text-5xl font-bold text-astral-500 mb-20">My Team</h1>
    <div class="grid grid-cols-2 gap-20">
      <div>
        <h2 class="text-xl mb-5">Add new team member</h2>
        <form class="space-y-5" @submit.prevent="addNewMember">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <div class="mt-1">
              <input type="text" name="name" id="name" v-model="personForm.name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md" placeholder="Calvin Hawkins" required>
            </div>
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1">
              <input type="text" name="email" id="email" v-model="personForm.email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md" placeholder="you@example.com" required>
            </div>
          </div>
          <div>
            <label for="photo" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
              Photo
            </label>
            <div class="mt-1">
              <div class="w-full flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <div>
                      <p class="text-xs text-gray-500 mb-2" v-if="personForm.photo"><strong>Selected file: </strong> {{ personForm.photo.name }}</p>
                      <div class="flex justify-center text-sm text-gray-600">
                          <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                              <span>Upload a file</span>
                              <input ref="photo" @click="clearExistingPhoto" @change="setPhoto" id="file-upload" name="file-upload" type="file" class="sr-only">
                          </label>
                          <p class="pl-1">or drag and drop</p>
                      </div>
                      <p class="text-xs text-gray-500">
                          JPG up to 100MB
                      </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <ul class="list-none md:list-disc list-inside" v-if="errors.length" v-for="(error, index) in errors" :key="index">
                <li v-text="error" class="text-red-500"></li>
          </ul>
          <button type="submit" v-text="newMemberFormSubmitText" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"></button>
        </form>
      </div>
      <div>
        <ul class="divide-y divide-gray-200">
          <li class="py-4 flex" v-for="(person, index) in people" :key="index">
            <img class="h-10 w-10 rounded-full" v-if="person.photo" :src="person.photo.thumbnails.large.url" :alt="person.name">
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900" v-text="person.name"></p>
              <p class="text-sm text-gray-500" v-text="person.email"></p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                errors: [],
                processing: false,
                people: [],
                personForm: {
                    name: null,
                    email: null,
                    photo: null
                }
            }
        },
        computed: {
            newMemberFormSubmitText() {
                return this.processing ? 'Processing...' : 'Submit'
            }
        },
        mounted() {
            this.fetchAllMembers();
        },
        methods: {
            fetchAllMembers() {
                axios.get('ajax/people')
                .then(response => {
                    this.people = response.data
                })
                .catch(error => {
                    alert('Failed to load team members data!')
                })
            },
            addNewMember() {
                this.processing = true

                axios.post('ajax/people', this.buildRequestData())
                .then(response => {
                    this.$set(this.people, this.people.length, response.data.data)
                    this.resetError()
                    alert(response?.data?.message || 'Successfully added new team member!')
                })
                .catch(error => {
                    console.log('reror', error);
                    alert(error?.response?.data?.message || 'Something went wrong!')
                    this.setError(error?.response?.data?.errors || {})
                })
                .finally(() => {
                    this.processing = false
                })
            },
            setPhoto() {
                if (!this.$refs.photo.files.length) {
                    alert('No photo selected!')
                }

                this.personForm.photo    = this.$refs.photo.files[0]
            },
            buildRequestData() {
                let formData = new FormData();

                Object.keys(this.personForm).forEach(key => {
                    if (null !== this.personForm[key]) {
                        formData.append(key, this.personForm[key])
                    }
                })

                return formData;
            },
            clearExistingPhoto() {
                this.personForm.photo = null
                this.$refs.photo.value = null
            },
            setError(errorResponse) {
                let errors = []
                Object.keys(errorResponse).forEach(key => {
                    errors = [...errors, ...errorResponse[key]]
                })
                this.errors = errors
            },
            resetError() {
                this.errors = []
            }
        }
    }
</script>
