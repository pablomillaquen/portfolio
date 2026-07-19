import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import CategoryFilter from '../../components/CategoryFilter.vue';

const categories = [
    { slug: 'tech', name: 'Technology' },
    { slug: 'design', name: 'Design' },
    { slug: 'life', name: 'Life' },
];

describe('CategoryFilter', () => {
    it('renders category buttons', () => {
        const wrapper = mount(CategoryFilter, {
            props: { categories },
        });
        const buttons = wrapper.findAll('button');
        expect(buttons).toHaveLength(3);
        expect(buttons[0].text()).toBe('Technology');
        expect(buttons[1].text()).toBe('Design');
        expect(buttons[2].text()).toBe('Life');
    });

    it('sets aria-pressed correctly', () => {
        const wrapper = mount(CategoryFilter, {
            props: { categories, selected: ['tech', 'life'] },
        });
        const buttons = wrapper.findAll('button');
        expect(buttons[0].attributes('aria-pressed')).toBe('true');
        expect(buttons[1].attributes('aria-pressed')).toBe('false');
        expect(buttons[2].attributes('aria-pressed')).toBe('true');
    });

    it('emits toggle event on click', async () => {
        const wrapper = mount(CategoryFilter, {
            props: { categories, selected: [] },
        });
        await wrapper.findAll('button')[1].trigger('click');
        expect(wrapper.emitted('toggle')).toHaveLength(1);
        expect(wrapper.emitted('toggle')[0]).toEqual(['design']);
    });
});
