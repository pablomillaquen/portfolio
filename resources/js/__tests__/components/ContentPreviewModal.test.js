import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { mount } from '@vue/test-utils';
import ContentPreviewModal from '../../components/ContentPreviewModal.vue';

vi.mock('@vueuse/integrations/useFocusTrap', () => ({
    useFocusTrap: () => ({
        activate: vi.fn(),
        deactivate: vi.fn(),
    }),
}));

describe('ContentPreviewModal', () => {
    let wrapper;

    const createWrapper = (props = {}) => {
        return mount(ContentPreviewModal, {
            props: {
                show: true,
                title: 'Test Title',
                html: '<p>Content</p>',
                locale: 'en',
                ...props,
            },
            global: {
                stubs: { Teleport: true },
            },
        });
    };

    afterEach(() => {
        wrapper?.unmount();
    });

    it('renders with role="dialog"', () => {
        wrapper = createWrapper();
        const dialog = wrapper.find('[role="dialog"]');
        expect(dialog.exists()).toBe(true);
    });

    it('sets aria-modal="true"', () => {
        wrapper = createWrapper();
        const dialog = wrapper.find('[role="dialog"]');
        expect(dialog.attributes('aria-modal')).toBe('true');
    });

    it('emits close on Escape key', async () => {
        wrapper = createWrapper();
        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
        await wrapper.vm.$nextTick();
        expect(wrapper.emitted('close')).toHaveLength(1);
    });
});
