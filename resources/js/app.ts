import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';
import { useAuthStore } from '@/stores/authStore';

const appName = import.meta.env.VITE_APP_NAME || 'TaskManager';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const pinia = createPinia();

    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(pinia);

    // Initialize auth store with user data from props
    const authStore = useAuthStore();
    if (props.initialPage.props.auth?.user) {
      authStore.initializeUser(props.initialPage.props.auth.user);
    }

    return app.mount(el);
  },
  progress: {
    color: '#4f46e5',
  },
});
