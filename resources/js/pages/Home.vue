<template>
    <div class="mx-auto lg:p-8">
        <div class="flex justify-between">
            <h1 class="text-3xl font-extrabold"> Doctors Appointment Management </h1>
            <span class="capitalize font-semibold">{{ userName }} <button
                class="btn btn-error"
                @click="handleLogout">Logout</button></span>
        </div>
        <template v-if="userRole === 'admin'">
            <AdminNav  />
        </template>
        <template v-else>

        </template>
    </div>
</template>
<script>
import {ref, onMounted} from 'vue'
import {useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import AdminNav from '../components/admin/AdminNav.vue';

export default {
    components: {
        Loader,
        AdminNav,
    },
    setup() {
        const user = ref()
    //     // const isLoading = ref()

        let router = useRouter();
        onMounted(() => {
            authentication()
        });
        const userID = localStorage.getItem('UserID');
        const userName = localStorage.getItem('UserName');
        const userEmail = localStorage.getItem('UserEmail');
        const userRole = localStorage.getItem('UserRole');
        // console.log(userID, userName, userEmail, userRole);
        const authentication = async () => {
            isLoading.value = true
            try {
                const req = await request('get', '/api/user')
                user.value = req.data
            } catch (e) {
                await router.push('/')
            }
        }
        
        const handleLogout = () => {
            localStorage.removeItem('APP_DEMO_USER_TOKEN')
            localStorage.removeItem('User')
            router.push('/')
        }
        // localStorage.setItem('Key', 1);
        console.log(localStorage.getItem('Key'));
        return {
            // Key,
            user,
            userName, 
            userEmail,
            userRole,
            handleLogout,
        }
    },
    
}
</script>