import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';
import { createPinia } from 'pinia';

const appName = import.meta.env.VITE_APP_NAME || 'TaskManager';

console.log('App.ts: Starting to create Inertia app...');

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => {
    console.log('App.ts: Resolving component:', name);
    return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue'));
  },
  setup({ el, App, props, plugin }) {
    console.log('App.ts: Setting up Vue app...');
    console.log('App.ts: Element:', el);
    console.log('App.ts: App component:', App);
    console.log('App.ts: Props:', props);

    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(createPinia());

    console.log('App.ts: Mounting Vue app...');
    const mountedApp = app.mount(el);
    console.log('App.ts: Vue app mounted successfully:', mountedApp);

    return mountedApp;
  },
  progress: {
    color: '#4f46e5',
  },
}).then(() => {
  console.log('App.ts: Inertia app created successfully!');
}).catch((error) => {
  console.error('App.ts: Error creating Inertia app:', error);
});
