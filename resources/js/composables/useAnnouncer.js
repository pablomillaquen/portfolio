import { ref, nextTick } from 'vue';

const politeMessage = ref('');
const assertiveMessage = ref('');

export function useAnnouncer() {
    async function announce(message, politeness = 'polite') {
        if (politeness === 'assertive') {
            assertiveMessage.value = '';
            await nextTick();
            assertiveMessage.value = message;
        } else {
            politeMessage.value = '';
            await nextTick();
            politeMessage.value = message;
        }
    }

    return { announce, politeMessage, assertiveMessage };
}
