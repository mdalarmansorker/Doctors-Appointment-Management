<template>
    <div class="mx-auto w-4/12 mt-10 bg-blue-200 p-4 rounded-lg">
        <!-- Loading UI -->
        <div v-if="loading" class="flex justify-center p-36">
            <!-- <Loader /> -->
            <a-spin size="large" />

        </div>
        <!-- component -->
        <div v-else
            class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-2 flex flex-col"
        >
            <h1 class="text-gray-600 py-5 font-bold text-3xl">DAM Login </h1>
            <ul class="list-disc text-red-400" v-for="(value, index) in errors" :key="index" v-if="typeof errors === 'object'">
                <li>{{value[0]}}</li>
            </ul>
            <p class="list-disc text-red-400" v-if="typeof errors === 'string'">{{errors}}</p>
            <form method="post" @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label
                        class="block text-grey-darker text-sm font-bold mb-2"
                        for="username"
                    >
                        Email Address
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker"
                        id="username"
                        type="text"
                        v-model="form.email"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        class="block text-grey-darker text-sm font-bold mb-2"
                        for="password"
                    >
                        Password
                    </label>
                    <input
                        class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3"
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                    />
                    <!-- <p class="text-red text-xs italic">Please choose a password.</p> -->
                </div>
                <div class="flex items-center justify-between">
                    <button
                        class="bg-blue-500 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded"
                        type="submit"
                    >
                        Sign In
                    </button>
                    <!-- <router-link
                        class="inline-block align-baseline font-bold text-sm text-blue hover:text-blue-darker"
                        to="register"
                    >
                        Sign Up
                    </router-link> -->
                </div>

                

            </form>
        </div>
    </div>
</template>

<script>
import { reactive, ref } from 'vue';
// import { Button } from 'ant-design-vue';
import axios from 'axios';
import Loader from '../components/Loader.vue';
import {useRouter} from "vue-router";
export default {
    setup() {
        const errors = ref()
        const router = useRouter();
        const loading = ref(false);
        const form = reactive({
            email: '',
            password: '',
        })
        const handleLogin = async () => {
            loading.value = true; // Set loading to true when login request starts
            try {
                const result = await axios.post('/api/auth/login', form);
                if (result.status === 200 && result.data && result.data.token) {
                    localStorage.setItem('APP_DEMO_USER_TOKEN', result.data.token);
                    localStorage.setItem('UserName', result.data.user.name);
                    localStorage.setItem("UserID", result.data.user.id);
                    localStorage.setItem('UserEmail', result.data.user.email);
                    localStorage.setItem('UserRole', result.data.roles);
                    await router.push('home');
                }
            } catch (e) {
                if(e && e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors);
                } else {
                    errors.value = e.response.data.message || "";
                }
            } finally {
                loading.value = false; // Set loading to false when login request ends
            }
        }

        return {
            form,
            loading,
            errors,
            handleLogin,
        }
    }
}
</script>