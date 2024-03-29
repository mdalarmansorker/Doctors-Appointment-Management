<template>
    <div>
        <a-button @click="showModal" class="mt-4">Create User</a-button>
        <a-modal v-model:visible="visible" title="Create User" @ok="handleOk" :ok-button-props="{ type: 'default' }">
            <form method="post" @submit="submitForm">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Name</span>
                    </label>
                    <input id="name" v-model="form.name" type="text" placeholder="Name" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Email</span>
                    </label>
                    <input id="email" v-model="form.email" type="email" placeholder="Email" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Password</span>
                    </label>
                    <input id="password" v-model="form.password" type="password" placeholder="Password" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text text-black">Select Role</span>
                    </label>
                    <div class="radio-group">
                        <label v-for="role in roles" :key="role.id" class="">
                            <input type="radio" v-model="form.role" :value="role.id" class="" />
                            <span class=""></span>
                            {{ role.name }}
                        </label>
                    </div>
                </div>
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">{{ editMode ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </a-modal>
    </div>
    <h1 class="text-3xl font-bold text-center">Active Accounts</h1>

    <div class="w-full flex justify-center flex-col">
        <a-table :data-source="users" class="w-full">
            <a-table-column title="#" width="">
                <template #default="{ record, index }">
                    {{ index + 1 }}
                </template>
            </a-table-column>
            <a-table-column key="name" title="Name" data-index="name" />
            <a-table-column key="email" title="Email" data-index="email" />
            <a-table-column key="roles" title="Roles" data-index="roles">
                <template #default="{ record }">
                    <span v-for="role in record.roles" :key="role.id">{{ role.name }}</span>
                </template>
            </a-table-column> 
            <a-table-column key="active" title="Active" data-index="active" >
                <template #default="{ text, record, index }">
                    <a-button @click="handleInactive(record)" v-if="record.active">
                        Active
                    </a-button>
                    <a-button @click="handleActive(record)" v-if="!record.active">
                        Inactive
                    </a-button>
                </template>
            </a-table-column>       
            <a-table-column key="action" title="Action">
                <template #default="{ text, record, index }">
                    <span class="flex gap-2">
                        <a-button @click="handleEdit(record)">Edit</a-button>
                        <a-button danger @click="showDeleteConfirm(record)">Delete</a-button>
                    </span>
                </template>
            </a-table-column>
        </a-table>
        <h1 class="text-3xl font-bold text-center">Deleted Accounts</h1>
        <a-table :data-source="deletedUsers" class="w-full">
            <a-table-column title="#" width="">
                <template #default="{ record, index }">
                    {{ index + 1 }}
                </template>
            </a-table-column>
            <a-table-column key="name" title="Name" data-index="name" />
            <a-table-column key="email" title="Email" data-index="email" />
            <a-table-column key="roles" title="Roles" data-index="roles">
                <template #default="{ record }">
                    <span v-for="role in record.roles" :key="role.id">{{ role.name }}</span>
                </template>
            </a-table-column>     
            <a-table-column key="action" title="Action">
                <template #default="{ text, record, index }">
                    <span class="flex gap-2">
                        <a-button @click="handleRestore(record)">Restore</a-button>
                        <a-button danger @click="showPermanentDeleteConfirm(record)">Delete</a-button>
                    </span>
                </template>
            </a-table-column>
        </a-table>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, createVNode, reactive } from 'vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import { Modal } from 'ant-design-vue';
import axios from 'axios';

export default defineComponent({
    setup() {
        const users = ref([]);
        const deletedUsers = ref([]);
        const roles = ref([]);
        const visible = ref<boolean>(false);
        const errors = ref();
        const editMode = ref<boolean>(false); // Track if the form is in edit mode
        const editUserId = ref<number | null>(null); // Track the ID of the role being edited
        const form = reactive({
            name: '',
            email: '',
            password: '',
            role_id: ''
        });
        const fetchUser = async () => {
            try {
                const response = await axios.get('/api/users');
                users.value = response.data;
                // console.log(users);
            } catch (error) {
                console.error('Error getting users', error)
            }
        };
        const fetchDeletedUser = async() => {
            try{
                const response = await axios.get('/api/deletedUsers');
                deletedUsers.value = response.data;
                console.log(deletedUsers);
            } catch (error) {
                console.error('Error getting deleted users', error);
            }
        }
        const fetchRole = async () => {
            try {
                const response = await axios.get('/api/roles');
                roles.value = response.data;
                // console.log(roles);
            } catch (error) {
                console.error('Error fetching roles:', error);
            }
        };

        const handleEdit = (record) => {
            editUserId.value = record.id;
            form.name = record.name;
            form.email = record.email;
            form.password = '';
            visible.value = true;
            editMode.value = true;
        };
        const handleActive = (record) => {
            Modal.confirm({
                title: 'Are you sure active this user?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'The user can log in if the account is active.',
                okText: 'Yes',
                okType: 'danger',
                cancelText: 'No',
                onOk() {
                    console.log('OK');
                    updateActiveStatus(record);
                },
                onCancel() {
                    console.log('Cancel');
                },
            });
        }
        const handleInactive = (record) => {
            Modal.confirm({
                title: 'Are you sure inactive this user?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'The user cannot log in if the account is inactive.',
                okText: 'Yes',
                okType: 'danger',
                cancelText: 'No',
                onOk() {
                    console.log('OK');
                    updateActiveStatus(record);
                },
                onCancel() {
                    console.log('Cancel');
                },
            });
        }
        const handleRestore = (record) => {
            Modal.confirm({
                title: 'Are you sure restoring this user?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'The user cannot log in if the account is inactive.',
                okText: 'Yes',
                okType: 'danger',
                cancelText: 'No',
                onOk() {
                    console.log('OK');
                    RestoreUser(record);
                },
                onCancel() {
                    console.log('Cancel');
                },
            });
        }
        const RestoreUser = async (record) => {
            try {
                await axios.put(`/api/user/restore/${record.id}`);
                fetchUser();
                fetchDeletedUser();
                console.log('User Restored');
            } catch (error) {
                console.error('Error restoring user');
            }
        }
        const PermanentDelete = async (record) => {
            try {
                await axios.delete(`/api/users/permanentDelete/${record.id}`);
                fetchDeletedUser();
                console.log('User permanently deleted');
            } catch (error) {
                console.log('Error deleting user');
            }
        }
        const updateActiveStatus = async (record) => {
            try {
                await axios.put(`/api/users/${record.id}/${record.active}`);
                fetchUser(); // Fetch updated data to refresh the table
                console.log('Active status updated');
            } catch (error) {
                console.error('Error updating active status user:', error);
            }
        };
        onMounted(() => {
            fetchRole();
            fetchUser();
            fetchDeletedUser();
        });

        const showModal = () => {
            form.name = '';
            form.email = '';
            form.role = '';
            editMode.value = false;
            visible.value = true;
            console.log('Show modal:', visible);
        };

        const handleOk = (e: MouseEvent) => {
            console.log(e);
            visible.value = false;
        };

        

        const submitForm = async (evt) => {
            evt.preventDefault();
            try {
                const url = editMode.value ? `/api/users/${editUserId.value}` : '/api/auth/register';
                const method = editMode.value ? 'put' : 'post';
                const response = await axios[method](url, form);
                console.log('User created/updated:', response.data);
               
                visible.value = false; // Hide the modal after successful creation/update
                fetchUser(); // Fetch updated data to refresh the table
                editMode.value = false; // Reset edit mode
                editUserId.value = null; // Reset edit role ID
            } catch (e) {
                if (e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors);
                }
            }
        };

        const showDeleteConfirm = (record) => {
            Modal.confirm({
                title: 'Are you sure deleting this user?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'This action cannot be undone!',
                okText: 'Yes',
                okType: 'danger',
                cancelText: 'No',
                onOk() {
                    console.log('OK');
                    handleDelete(record);
                },
                onCancel() {
                    console.log('Cancel');
                },
            });
        };
        const showPermanentDeleteConfirm = (record) => {
            Modal.confirm({
                title: 'Are you sure delete this user permanently?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'This action cannot be undone!',
                okText: 'Yes',
                okType: 'danger',
                cancelText: 'No',
                onOk() {
                    console.log('OK');
                    PermanentDelete(record);
                },
                onCancel() {
                    console.log('Cancel');
                },
            });
        };

        const handleDelete = async (record) => {
            try {
                await axios.delete(`/api/users/${record.id}`);
                fetchUser(); 
                fetchDeletedUser();
                console.log('User deleted successfully');
            } catch (error) {
                console.error('Error deleting user:', error);
            }
        };

        return {
            users,
            deletedUsers,
            roles,
            handleEdit,
            handleDelete,
            handleRestore,
            showDeleteConfirm,
            showPermanentDeleteConfirm,
            visible,
            showModal,
            handleOk,
            form,
            errors,
            submitForm,
            editMode,
            handleActive,
            handleInactive,
        };
    },
});
</script>
