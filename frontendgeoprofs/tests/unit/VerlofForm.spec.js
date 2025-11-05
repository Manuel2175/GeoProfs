import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import VerlofForm from '@/components/VerlofForm.vue';
import VerlofService from '@/services/VerlofService.js';
import AuthService from '@/services/AuthService.js';
import { createRouter, createMemoryHistory } from 'vue-router';

vi.mock('@/services/VerlofService.js');
vi.mock('@/services/AuthService.js');

const router = createRouter({
  history: createMemoryHistory(),
  routes: [
    { path: '/', name: 'Home' },
    { path: '/dashboard', name: 'Dashboard' },
  ],
});

beforeEach(() => {
  router.push('/');
  AuthService.getCurrentUser.mockReturnValue({ id: 1 });
});

test('renders all form fields and submit button', async () => {
  render(VerlofForm, { global: { plugins: [router] } });

  const startInput = screen.getByLabelText(/begin datum/i);
  const endInput = screen.getByLabelText(/eind datum/i);
  const reasonInput = screen.getByPlaceholderText(/bijvoorbeeld: privé afspraak/i);
  const submitButton = screen.getByRole('button', { name: /verlof aanvragen/i });

  expect(startInput).toBeInTheDocument();
  expect(endInput).toBeInTheDocument();
  expect(reasonInput).toBeInTheDocument();
  expect(submitButton).toBeInTheDocument();
});

test('submits form and redirects on success', async () => {
  VerlofService.aanvragen.mockResolvedValue({});

  render(VerlofForm, { global: { plugins: [router] } });

  const startInput = screen.getByLabelText(/begin datum/i);
  const endInput = screen.getByLabelText(/eind datum/i);
  const reasonInput = screen.getByPlaceholderText(/bijvoorbeeld: privé afspraak/i);
  const submitButton = screen.getByRole('button', { name: /verlof aanvragen/i });

  await fireEvent.update(startInput, '2025-11-01');
  await fireEvent.update(endInput, '2025-11-05');
  await fireEvent.update(reasonInput, 'Test reden');

  await fireEvent.click(submitButton);

  await waitFor(() => {
    expect(router.currentRoute.value.path).toBe('/dashboard');
  });
});
