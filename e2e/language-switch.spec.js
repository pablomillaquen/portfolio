import { test, expect } from '@playwright/test';
import { urls } from './fixtures/data.js';

test.describe('Language Switch', () => {
    test('language toggle button exists', async ({ page }) => {
        await page.goto(urls.home);

        const toggle = page.getByRole('button', { name: /lang|idioma|es|en|toggle.*lang/i });
        await expect(toggle).toBeVisible();
    });

    test('toggling language changes page content', async ({ page }) => {
        await page.goto(urls.home);
        await page.waitForLoadState('networkidle');

        const bodyTextBefore = await page.textContent('body');

        const toggle = page.getByRole('button', { name: /lang|idioma|es|en|toggle.*lang/i });
        await toggle.click();
        await page.waitForTimeout(1000);

        const bodyTextAfter = await page.textContent('body');
        expect(bodyTextAfter).not.toBe(bodyTextBefore);
    });

    test('language preference persists on navigation', async ({ page }) => {
        await page.goto(urls.home);
        await page.waitForLoadState('networkidle');

        const toggle = page.getByRole('button', { name: /lang|idioma|es|en|toggle.*lang/i });
        await toggle.click();
        await page.waitForTimeout(500);

        const langAfterToggle = await page.textContent('body');

        await page.getByRole('link', { name: /proyectos/i }).first().click();
        await page.waitForLoadState('networkidle');

        const langOnProjects = await page.textContent('body');
        const isSameLanguage =
            langAfterToggle.includes('Projects') === langOnProjects.includes('Projects') ||
            langAfterToggle.includes('Proyectos') === langOnProjects.includes('Proyectos');

        expect(isSameLanguage).toBeTruthy();
    });
});
