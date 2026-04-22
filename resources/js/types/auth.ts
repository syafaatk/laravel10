export interface User {
  id: number;
  name: string;
  email: string;
  role?: string;
  avatar?: string;
}

export interface AuthResponse {
  user: User;
  message?: string;
}
