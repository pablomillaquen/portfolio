import { test, expect } from '@playwright/test';
import { urls } from './fixtures/data.js';

test.describe('Navigation', () => {
    test('home page loads with correct title', async ({ page }) => {
        await page.goto(urls.home);
        await expect(page).toHaveTitle(/Pablo Millaquen/);
    });

    test('navigate to projects page', async ({ page }) => {
        await page.goto(urls.home);
        await page.getByRole('link', { name: /proyectos/i }).first().click();
        await expect(page).toHaveURL(/\/projects/);
    });

    test('navigate to posts page', async ({ page }) => {
        await page.goto(urls.home);
        await page.getByRole('link', { name: /publicaciones/i }).first().click();
        await expect(page).toHaveURL(/\/posts/);
    });

    test('navigate to courses page', async ({ page }) => {
        await page.goto(urls.home);
        await page.getByRole('link', { name: /cursos/i }).first().click();
        await expect(page).toHaveURL(/\/courses/);
    });

    test('navigate to contact page', async ({ page }) => {
        await page.goto(urls.home);
        await page.getByRole('link', { name: /contacto/i }).first().click();
        await expect(page).toHaveURL(/\/contact/);
    });

    test('page has no console errors', async ({ page }) => {
        const errors = [];
        page.on('console', (msg) => {
            if (msg.type() === 'error') errors.push(msg.text());
        });
        await page.goto(urls.home);
        await page.waitForLoadState('networkidle');
        expect(errors).toEqual([]);
    });
});
