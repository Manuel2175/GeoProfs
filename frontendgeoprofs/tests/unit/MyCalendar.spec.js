import { render, screen, fireEvent, waitFor } from '@testing-library/vue';
import MyCalendar from '@/components/MyCalendar.vue';
import RoosterService from '@/services/RoosterService.js';
import AuthService from '@/services/AuthService.js';

vi.mock('@/services/RoosterService.js', () => ({
  default: {
    getRoosterByWeek: vi.fn(),
    getAanwezigen: vi.fn(),
    getAfwezigen: vi.fn(),
  },
}));

vi.mock('@/services/AuthService.js', () => ({
  default: {
    getCurrentUser: vi.fn(),
  },
}));

beforeEach(() => {
  AuthService.getCurrentUser.mockResolvedValue({ id: 1 });

  RoosterService.getRoosterByWeek.mockResolvedValue({
    dagen: [
      {
        id: 1,
        name: 'Maandag',
        date: '01-01-2025',
        ochtend: true,
        middag: false,
      },
      {
        id: 2,
        name: 'Dinsdag',
        date: '02-01-2025',
        ochtend: false,
        middag: true,
      },
    ],
  });

  RoosterService.getAanwezigen.mockResolvedValue(8);
  RoosterService.getAfwezigen.mockResolvedValue(2);
});

test('toont rooster en statistieken bij laden', async () => {
  render(MyCalendar);

  await screen.findByText('100%');

  expect(screen.getByText(/week 1/i)).toBeInTheDocument();
  expect(screen.getByText('Maandag')).toBeInTheDocument();
  expect(screen.getByText('Dinsdag')).toBeInTheDocument();

  expect(screen.getAllByText('Aanwezig').length).toBeGreaterThan(0);
  expect(screen.getAllByText('Vrij').length).toBeGreaterThan(0);
});

test('verhoogt weeknummer en haalt nieuw rooster op bij klikken op >', async () => {
  render(MyCalendar);

  const nextButton = screen.getByTestId('next-week');

  await fireEvent.click(nextButton);

  await waitFor(() => {
    expect(screen.getByText(/week 2/i)).toBeInTheDocument();
  });

  expect(RoosterService.getRoosterByWeek).toHaveBeenLastCalledWith(1, 2);
});

test('verlaagt weeknummer bij klikken op <', async () => {
  render(MyCalendar);

  const nextButton = screen.getByTestId('next-week');
  const prevButton = screen.getByTestId('prev-week');

  await fireEvent.click(nextButton);
  await fireEvent.click(prevButton);

  await waitFor(() => {
    expect(screen.getByText(/week 1/i)).toBeInTheDocument();
  });
});

test('kan niet lager dan week 1 navigeren', async () => {
  render(MyCalendar);

  const prevButton = screen.getByTestId('prev-week');
  expect(prevButton).toBeDisabled();
});
