<template>
  <div class="flex gap-8">
    <div>
      <a-menu
        v-model:openKeys="openKeys"
        v-model:selectedKeys="selectedKeys"
        style="width: 256px"
        mode="vertical"
        @click="handleClick"
      >
        <a-menu-item key="1">
          <template #icon>
            <HomeOutlined />
          </template>
          Home
        </a-menu-item>
        <a-menu-item key="2">
          <template #icon>
            <UsergroupAddOutlined />
          </template>
          Users
        </a-menu-item>
        <a-menu-item key="3">
          <template #icon>
            <UserOutlined />
          </template>
          Roles
        </a-menu-item>
        <a-menu-item key="4">
          <template #icon>
            <TagOutlined />
          </template>
          Permissions
        </a-menu-item>
      </a-menu>
    </div>
    <div class="items-center">
      <template v-if="selectedTemplate === 'Home'">
        <!-- <AdminHome /> -->
        <RolesTesting />
      </template>
      <template v-else-if="selectedTemplate === 'User Registration'">
        <Users />
      </template>
      <template v-else-if="selectedTemplate === 'Create Roles'">
        <Roles />
      </template>
      <template v-else-if="selectedTemplate === 'Create Permissions'">
        <Permissions />
      </template>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from 'vue';
import {
  HomeOutlined,
  UsergroupAddOutlined,
  CalendarOutlined,
  UserOutlined,
  TagOutlined,
} from '@ant-design/icons-vue';
import type { MenuProps } from 'ant-design-vue';
import AdminHome from './AdminHome.vue';
import Users from './Users.vue';
import Roles from './Roles.vue';
import Permissions from './Permissions.vue';
import RolesTesting from './RolesTesting.vue';

export default defineComponent({
  components: {
    HomeOutlined,
    UsergroupAddOutlined,
    CalendarOutlined,
    UserOutlined,
    TagOutlined,
    AdminHome,
    Users,
    Roles,
    Permissions,
    RolesTesting,
  },
  setup() {
    const state = reactive({
      selectedKey: null,
      openKeys: [],
    });
    const selectedTemplate = ref('Home'); // Default template to show

    const handleClick: MenuProps['onClick'] = menuInfo => {
      switch (menuInfo.key) {
        case '1':
          selectedTemplate.value = 'Home';
          break;
        case '2':
          selectedTemplate.value = 'User Registration';
          break;
        case '3':
          selectedTemplate.value = 'Create Roles';
          break;
        case '4':
          selectedTemplate.value = 'Create Permissions';
          break;
        default:
          selectedTemplate.value = 'Home';
      }
    };

    return {
      selectedTemplate,
      state,
      handleClick,
    };
  },
});
</script>
