<template>
    <div class="mx-auto lg:p-8">
        <div class="flex justify-between">
            <h1 class="text-3xl font-extrabold"> Doctors Appointment Management </h1>
            <span class="capitalize font-semibold">{{ userName }} <button
                class="btn btn-error"
                @click="handleLogout">Logout</button></span>
        </div>
        <template v-if="userRole === 'admin'">
            <div class="flex">
                <AdminNav  @menu-item-clicked="handleMenuItemClicked"/>
                <div v-if="adminNavState.selectedKey === '1'">
                    <AdminHome/>
                </div>
            </div>
            
            
        </template>
        <template v-else>

        </template>
    </div>
</template>
<script>
import {ref, onMounted, reactive} from 'vue'
import {useRouter} from "vue-router";
import {request} from '../helper'
import Loader from '../components/Loader.vue';
import AdminNav from '../components/admin/AdminNav.vue';
import AdminHome from '../components/admin/AdminHome.vue';
export default {
    components: {
        Loader,
        AdminNav,
        AdminHome,
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
        // Create a reactive state to hold the selected key from AdminNav
        const adminNavState = reactive({
            selectedKey: null,
        });
        const handleMenuItemClicked = (key) => {
            adminNavState.selectedKey = key;
        };
        return {
            adminNavState,
            handleMenuItemClicked,
            user,
            userName, 
            userEmail,
            userRole,
            handleLogout,
        }
    },
    
}
</script>