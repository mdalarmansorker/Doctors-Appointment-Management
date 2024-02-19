<template>
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
      User Registration
    </a-menu-item>
    <a-menu-item key="3">
      <template #icon>
        <CalendarOutlined />
      </template>
      Create Roles
    </a-menu-item>
    <a-menu-item key="4">
      <template #icon>
        <CalendarOutlined />
      </template>
      Create Permissions
    </a-menu-item>
    
  </a-menu>
</template>
<script lang="ts">
  import { defineComponent, reactive, toRefs } from 'vue';
  import {
    HomeOutlined,
    UsergroupAddOutlined,
    MailOutlined,
    CalendarOutlined,
    AppstoreOutlined,
    SettingOutlined,
  } from '@ant-design/icons-vue';
  import type { MenuProps } from 'ant-design-vue';

  export default defineComponent({
    components: {
      HomeOutlined,
      UsergroupAddOutlined,
      MailOutlined,
      CalendarOutlined,
      AppstoreOutlined,
      SettingOutlined,
    },
    setup() {
      const state = reactive({
        selectedKey: null,
        openKeys: [],
      });
      const handleClick: MenuProps['onClick'] = menuInfo => {
        console.log('click ', menuInfo);
        // Update the selectedKey when a menu item is clicked
        state.selectedKey = menuInfo.key;
      };
      return {
        // ...toRefs(state),
        state,
        handleClick,
      };
    },
  });
</script>

