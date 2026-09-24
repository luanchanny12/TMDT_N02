# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {SESSION_COOKIE}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Đăng nhập tại <b>/login</b> để có session cookie. Các endpoint cần auth sẽ được đánh dấu <code>🔒 Requires authentication</code>.
