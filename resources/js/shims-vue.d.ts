declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}

declare module '@inertiajs/vue3' {
  export * from '@inertiajs/core'
  export { default as Link } from '@inertiajs/vue3/Link'
  export { default as Head } from '@inertiajs/vue3/Head'
}

declare module 'laravel-vite-plugin/inertia-helpers' {
  export function resolvePageComponent(
    path: string,
    pages: Record<string, any>
  ): Promise<any>
}