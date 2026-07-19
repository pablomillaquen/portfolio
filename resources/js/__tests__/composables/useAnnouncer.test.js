import { describe, it, expect, beforeEach } from 'vitest';
import { useAnnouncer } from '../../composables/useAnnouncer';

describe('useAnnouncer', () => {
    let announce, politeMessage, assertiveMessage;

    beforeEach(() => {
        const result = useAnnouncer();
        announce = result.announce;
        politeMessage = result.politeMessage;
        assertiveMessage = result.assertiveMessage;
        politeMessage.value = '';
        assertiveMessage.value = '';
    });

    it('returns announce function', () => {
        expect(typeof announce).toBe('function');
    });

    it('announce updates politeMessage by default', async () => {
        await announce('Hello world');
        expect(politeMessage.value).toBe('Hello world');
    });

    it('announce with assertive updates assertiveMessage', async () => {
        await announce('Urgent alert', 'assertive');
        expect(assertiveMessage.value).toBe('Urgent alert');
        expect(politeMessage.value).toBe('');
    });

    it('announce clears before setting for screen reader re-announcement', async () => {
        await announce('First message');
        expect(politeMessage.value).toBe('First message');

        await announce('Second message');
        expect(politeMessage.value).toBe('Second message');
    });
});
