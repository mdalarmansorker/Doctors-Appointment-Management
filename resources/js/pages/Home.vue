<template>
    <div class="mx-auto lg:p-8">
        <div class="flex justify-between">
            <h1 class="text-3xl font-extrabold"> Doctors Appointment Management </h1>
            <span class="capitalize font-semibold">{{ userName }} <button
                class="btn btn-error"
                @click="handleLogout">Logout</button></span>
        </div>
    </div>
</template>
<script>
import {ref, onMounted} from 'vue'
import {useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';

export default {
    components: {
        Loader,
    },
    setup() {
        const user = ref()
    //     // const isLoading = ref()

        let router = useRouter();
        onMounted(() => {
            authentication()
        });

        const authentication = async () => {
            isLoading.value = true
            try {
                const req = await request('get', '/api/user')
                user.value = req.data
            } catch (e) {
                await router.push('/')
            }
        }
        const userName = localStorage.getItem('UserName');
        const userEmail = localStorage.getItem('UserEmail');
        const handleLogout = () => {
            localStorage.removeItem('APP_DEMO_USER_TOKEN')
            localStorage.removeItem('User')
            router.push('/')
        }
        return {
            user,
            userName, 
            userEmail,
            handleLogout,
        }
    },
}
</script>