import { ref, watch } from 'vue';

const initialValue = typeof sessionStorage !== 'undefined' ? sessionStorage.getItem('isSystemUnlocked') === 'true' : false;
export const isSystemUnlocked = ref(initialValue);

if (typeof window !== 'undefined') {
    watch(isSystemUnlocked, (newValue) => {
        sessionStorage.setItem('isSystemUnlocked', newValue);
    });
}
