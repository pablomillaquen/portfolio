import { test, expect } from '@playwright/test';
import { urls } from './fixtures/data.js';

test.describe('Contact Form', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto(urls.contact);
    });

    test('contact form is visible on page', async ({ page }) => {
        await expect(page.getByRole('form')).toBeVisible();
        await expect(page.getByLabel(/nombre/i)).toBeVisible();
        await expect(page.getByLabel(/email/i)).toBeVisible();
        await expect(page.getByLabel(/mensaje/i)).toBeVisible();
    });

    test('submitting empty form shows validation errors', async ({ page }) => {
        await page.getByRole('button', { name: /enviar/i }).click();

        await expect(page.getByText(/requerido/i).first()).toBeVisible();
    });

    test('filling and submitting form shows success message', async ({ page }) => {
        await page.getByLabel(/nombre/i).fill('Test User');
        await page.getByLabel(/email/i).fill('test@example.com');
        await page.getByLabel(/mensaje/i).fill('This is a test message from E2E.');

        await page.getByRole('button', { name: /enviar/i }).click();

        await expect(
            page.getByText(/enviado|éxito|success/i).first()
        ).toBeVisible({ timeout: 10000 });
    });
});
