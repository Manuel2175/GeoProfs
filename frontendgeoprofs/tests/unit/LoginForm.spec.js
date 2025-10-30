import { render, screen, fireEvent } from '@testing-library/vue';
import { nextTick } from 'vue';
import LoginForm from '@/components/LoginForm.vue';
import AuthService from '@/services/AuthService';
import { useRouter } from 'vue-router';

// -----------------------------
// Mocks
// -----------------------------
vi.mock('@/services/AuthService');
vi.mock('vue-router', () => ({
  useRouter: vi.fn()
}));

describe('LoginForm.vue', () => {
  const mockPush = vi.fn();

  beforeEach(() => {
    useRouter.mockReturnValue({ push: mockPush });
    vi.clearAllMocks();
  });

  it('renders inputs and submit button', () => {
    render(LoginForm);

    expect(screen.getByPlaceholderText('Voorbeeldnaam')).toBeInTheDocument();
    expect(screen.getByPlaceholderText('********')).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /inloggen/i })).toBeInTheDocument();
  });

  it('calls AuthService.login with username and password', async () => {
    AuthService.login.mockResolvedValueOnce({ token: '123' });

    render(LoginForm);

    const usernameInput = screen.getByPlaceholderText('Voorbeeldnaam');
    const passwordInput = screen.getByPlaceholderText('********');
    const button = screen.getByRole('button', { name: /inloggen/i });

    await fireEvent.update(usernameInput, 'testuser');
    await fireEvent.update(passwordInput, 'secret');
    await fireEvent.click(button);

    expect(AuthService.login).toHaveBeenCalledWith('testuser', 'secret');
    expect(mockPush).toHaveBeenCalledWith('/dashboard');
  });

  it('shows error message when login fails', async () => {
    AuthService.login.mockRejectedValueOnce({ response: { data: { message: 'Invalid credentials' } } });

    render(LoginForm);

    const usernameInput = screen.getByPlaceholderText('Voorbeeldnaam');
    const passwordInput = screen.getByPlaceholderText('********');
    const button = screen.getByRole('button', { name: /inloggen/i });

    await fireEvent.update(usernameInput, 'wronguser');
    await fireEvent.update(passwordInput, 'wrongpass');
    await fireEvent.click(button);

    expect(await screen.findByText('Invalid credentials')).toBeInTheDocument();
    expect(passwordInput.value).toBe('');
  });

  it('shows an error message and clears password on wrong credentials', async () => {
    AuthService.login.mockRejectedValueOnce({
      response: { data: { message: 'Invalid credentials' } }
    });

    render(LoginForm);

    const usernameInput = screen.getByPlaceholderText('Voorbeeldnaam');
    const passwordInput = screen.getByPlaceholderText('********');
    const button = screen.getByRole('button', { name: /inloggen/i });

    await fireEvent.update(usernameInput, 'wronguser');
    await fireEvent.update(passwordInput, 'wrongpass');

    await fireEvent.click(button);

    const errorMessage = await screen.findByText('Invalid credentials');

    expect(errorMessage).toBeInTheDocument();
    expect(passwordInput.value).toBe('');
    expect(usernameInput.value).toBe('wronguser');
  });


  it('disables inputs and button while loading', async () => {
    // simulate promise
    let resolveLogin;
    AuthService.login.mockImplementation(() => new Promise(r => { resolveLogin = r }));

    render(LoginForm);

    const usernameInput = screen.getByPlaceholderText('Voorbeeldnaam');
    const passwordInput = screen.getByPlaceholderText('********');
    const button = screen.getByRole('button', { name: /inloggen/i });

    await fireEvent.update(usernameInput, 'testuser');
    await fireEvent.update(passwordInput, 'secret');
    fireEvent.click(button);

    // wait for load state want vue wou ff huilen
    await nextTick();

    expect(usernameInput).toBeDisabled();
    expect(passwordInput).toBeDisabled();
    expect(button).toBeDisabled();

    resolveLogin();
  });
});
