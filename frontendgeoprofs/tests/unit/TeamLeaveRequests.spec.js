import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import TeamLeaveRequests from '@/components/TeamLeaveRequests.vue';
import AuthService from '@/services/AuthService';
import VerlofService from '@/services/VerlofService';

vi.mock('@/services/AuthService');
vi.mock('@/services/VerlofService');

vi.mock('@/services/axios-config.js', () => ({
  ensureCsrfToken: vi.fn().mockResolvedValue(true),
}));

describe('TeamLeaveRequests.vue', () => {
  const userId = 123;
  const verlofId = 1;
  const mockVerloven = [
    { id: verlofId, user_id: userId, user: { name: 'Test User' }, startdatum: '2025-11-01', einddatum: '2025-11-05', status: 'aangevraagd' }
  ];

  beforeEach(() => {
    vi.clearAllMocks();
    vi.stubGlobal('alert', vi.fn());
    AuthService.getCurrentUser.mockReturnValue({ id: userId });
    VerlofService.getVerloven.mockResolvedValueOnce({ aanvragen: mockVerloven });
  });

  it('keurt een verlofaanvraag goed en herlaadt de lijst', async () => {
    VerlofService.approve.mockResolvedValueOnce({});
    VerlofService.getVerloven.mockResolvedValueOnce({ aanvragen: [] });

    render(TeamLeaveRequests);

    const approveButton = await screen.findByText('Approve');
    expect(await screen.findByText('Test User')).toBeInTheDocument();

    await fireEvent.click(approveButton);

    await waitFor(() => {
      expect(VerlofService.approve).toHaveBeenCalledWith(userId, verlofId);
      expect(alert).toHaveBeenCalledWith('Verlof goedgekeurd!');
      expect(VerlofService.getVerloven).toHaveBeenCalledTimes(2);
      expect(screen.queryByText('Test User')).not.toBeInTheDocument();
    });
  });

  it('keurt een verlofaanvraag af met een reden en herlaadt de lijst', async () => {
    const redenTekst = 'redenen voor afkeur';
    VerlofService.reject.mockResolvedValueOnce({});
    VerlofService.getVerloven.mockResolvedValueOnce({ aanvragen: [] });

    render(TeamLeaveRequests);

    const denyButton = await screen.findByText('Deny');
    const redenInput = await screen.findByPlaceholderText('Reden voor afkeur');
    expect(await screen.findByText('Test User')).toBeInTheDocument();

    await fireEvent.update(redenInput, redenTekst);

    await fireEvent.click(denyButton);

    await waitFor(() => {
      expect(VerlofService.reject).toHaveBeenCalledWith(userId, verlofId, redenTekst);
      expect(alert).toHaveBeenCalledWith('Verlof afgekeurd!');
      expect(VerlofService.getVerloven).toHaveBeenCalledTimes(2);
      expect(screen.queryByText('Test User')).not.toBeInTheDocument();
    });
  });
});
