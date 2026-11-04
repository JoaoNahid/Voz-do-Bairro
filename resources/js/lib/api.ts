/**
 * API client helper for Voz do Bairro
 * Provides unified fetch calls with automatic CSRF, JSON parsing and error handling.
 */

function getCsrfToken(): string | null {
  const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
  if (meta && meta.content) {
    return meta.content;
  }
  // Fallback to cookie
  const match = document.cookie.match(new RegExp('(^|;\\s*)(XSRF-TOKEN)=([^;]*)'));
  return match ? decodeURIComponent(match[3]) : null;
}

export interface ApiResponse<T = any> {
  data: T;
  status: number;
}

export class ApiError extends Error {
  status: number;
  errors?: Record<string, string[]>;

  constructor(message: string, status: number, errors?: Record<string, string[]>) {
    super(message);
    this.name = 'ApiError';
    this.status = status;
    this.errors = errors;
  }
}

async function request<T = any>(
  endpoint: string,
  options: RequestInit = {}
): Promise<T> {
  const csrf = getCsrfToken();
  const headers: Record<string, string> = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    ...(options.headers as Record<string, string> || {}),
  };

  if (csrf) {
    headers['X-CSRF-TOKEN'] = csrf;
  }

  const response = await fetch(endpoint, {
    ...options,
    headers,
    credentials: 'same-origin',
  });

  const contentType = response.headers.get('content-type') || '';
  const isJson = contentType.includes('application/json');
  const body = isJson ? await response.json() : await response.text();

  if (!response.ok) {
    const message = body?.message || response.statusText || 'Erro na requisição';
    throw new ApiError(message, response.status, body?.errors);
  }

  return body as T;
}

export const api = {
  get: <T = any>(url: string) => request<T>(url, { method: 'GET' }),
  post: <T = any>(url: string, data?: any) =>
    request<T>(url, { method: 'POST', body: data !== undefined ? JSON.stringify(data) : undefined }),
  put: <T = any>(url: string, data?: any) =>
    request<T>(url, { method: 'PUT', body: data !== undefined ? JSON.stringify(data) : undefined }),
  delete: <T = any>(url: string, data?: any) =>
    request<T>(url, { method: 'DELETE', body: data !== undefined ? JSON.stringify(data) : undefined }),
};
