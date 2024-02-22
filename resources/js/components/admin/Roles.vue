<template>
    <div>
        <a-button @click="showModal">Create Role</a-button>
        <a-modal v-model:visible="visible" title="Create Role" @ok="handleOk" :ok-button-props="{ type: 'default' }">
            <form method="post" @submit="submitForm">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Role Name</span>
                    </label>
                    <input id="name" v-model="form.name" type="text" placeholder="Role Name" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text text-black">Select Permissions</span>
                    </label>
                    <div class="checkbox-group">
                        <label v-for="permission in permissions" :key="permission.id" class="">
                            <input type="checkbox" v-model="form.permissions" :value="permission.id" class="" />
                            <span class=""></span>
                            {{ permission.name }}
                        </label>
                    </div>
                </div>
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">{{ editMode ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </a-modal>
    </div>
    <a-table :data-source="role">
        <a-table-column title="#" width="50px">
            <template #default="{ record, index }">
                {{ index + 1 }}
            </template>
        </a-table-column>
        <a-table-column key="name" title="Role Name" data-index="name" />
        <a-table-column key="permissions" title="Permissions" data-index="permissions">
            <template #default="{ record }">
                <span>
                    <a-tag v-for="permission in record.permissions" :key="permission.id">
                        {{ permission.name }}
                    </a-tag>
                </span>
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
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, createVNode, reactive } from 'vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import { Modal } from 'ant-design-vue';
import axios from 'axios';

export default defineComponent({
    setup() {
        const role = ref([]);
        const permissions = ref([]);
        const visible = ref<boolean>(false);
        const errors = ref();
        const editMode = ref<boolean>(false); // Track if the form is in edit mode
        const editRoleId = ref<number | null>(null); // Track the ID of the role being edited
        const form = reactive({
            name: '',
            permissions: [],
        });
        const fetchRole = async () => {
            try {
                const response = await axios.get('/api/roles');
                role.value = response.data;
                console.log(role);
            } catch (error) {
                console.error('Error fetching roles:', error);
            }
        };

        const fetchPermissions = async () => {
            try {
                const response = await axios.get('/api/permissions');
                permissions.value = response.data;
            } catch (error) {
                console.error('Error fetching permissions:', error);
            }
        };

        const handleEdit = (record) => {
            editRoleId.value = record.id;
            form.name = record.name;
            form.permissions = record.permissions.map(permission => permission.id);
            visible.value = true;
            editMode.value = true;
        };

        onMounted(() => {
            fetchRole();
            fetchPermissions();
        });

        const showModal = () => {
            form.name = '';
            form.permissions = [];
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
                const url = editMode.value ? `/api/roles/${editRoleId.value}` : '/api/roles';
                const method = editMode.value ? 'put' : 'post';
                const response = await axios[method](url, form);
                console.log('Role created/updated:', response.data);
               
                visible.value = false; // Hide the modal after successful creation/update
                fetchRole(); // Fetch updated data to refresh the table
                editMode.value = false; // Reset edit mode
                editRoleId.value = null; // Reset edit role ID
            } catch (e) {
                if (e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors);
                }
            }
        };

        const showDeleteConfirm = (record) => {
            Modal.confirm({
                title: 'Are you sure delete this role?',
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

        const handleDelete = async (record) => {
            try {
                await axios.delete(`/api/roles/${record.id}`);
                fetchRole(); // Fetch updated data to refresh the table
                console.log('Role deleted successfully');
            } catch (error) {
                console.error('Error deleting role:', error);
            }
        };

        return {
            role,
            permissions,
            handleEdit,
            handleDelete,
            showDeleteConfirm,
            visible,
            showModal,
            handleOk,
            form,
            errors,
            submitForm,
            editMode,
        };
    },
});
</script>
