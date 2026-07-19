import { test, expect } from '@playwright/test';
import { admin, urls } from './fixtures/data.js';

test.describe('Admin CRUD', () => {
    test('admin page loads', async ({ page }) => {
        await page.goto(urls.admin);
        await expect(page).toHaveURL(/\/admin/);
    });

    test('login with valid credentials succeeds', async ({ page }) => {
        await page.goto(urls.admin);

        await page.getByLabel(/email/i).fill(admin.email);
        await page.getByLabel(/contraseña|password/i).fill(admin.password);
        await page.getByRole('button', { name: /iniciar sesión|login|entrar/i }).click();

        await expect(page).not.toHaveURL(/\/login|\/admin\?/, { timeout: 10000 });
        await expect(page.getByRole('heading', { name: /admin|panel|dashboard/i })).toBeVisible({ timeout: 10000 });
    });

    test('login with invalid credentials shows error', async ({ page }) => {
        await page.goto(urls.admin);

        await page.getByLabel(/email/i).fill('wrong@example.com');
        await page.getByLabel(/contraseña|password/i).fill('wrongpassword');
        await page.getByRole('button', { name: /iniciar sesión|login|entrar/i }).click();

        await expect(
            page.getByText(/credenciales|incorrecto|invalid/i).first()
        ).toBeVisible({ timeout: 10000 });
    });

    test('admin panel is accessible after login', async ({ page }) => {
        await page.goto(urls.admin);

        await page.getByLabel(/email/i).fill(admin.email);
        await page.getByLabel(/contraseña|password/i).fill(admin.password);
        await page.getByRole('button', { name: /iniciar sesión|login|entrar/i }).click();

        await page.waitForURL(/admin/, { timeout: 10000 });
        await expect(page.getByRole('heading', { name: /admin|panel|dashboard/i })).toBeVisible({ timeout: 10000 });
    });
});
