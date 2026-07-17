import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { createI18n } from 'vue-i18n';
import uz from './locales/uz.json';
import en from './locales/en.json';
import ru from './locales/ru.json';
import tr from './locales/tr.json';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const defaultLocale = props.initialPage.props.auth?.user?.ui_settings?.language || 'uz';
        
        const i18n = createI18n({
            locale: defaultLocale,
            fallbackLocale: 'uz',
            messages: { uz, en, ru, tr },
            legacy: false, // For Vue 3 Composition API
        });

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
