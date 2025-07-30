# Contributing Guide

Thank you for considering contributing to TaskFlow!

## Development Process

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Write or update tests
5. Run the test suite
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## Code Style

We use PHP-CS-Fixer to maintain consistent code style:

```bash
composer run fix
```

## Testing

Run the test suite before submitting:

```bash
composer test
```

## Commit Messages

Use conventional commit format:
- `feat:` new features
- `fix:` bug fixes
- `docs:` documentation changes
- `style:` formatting changes
- `refactor:` code refactoring
- `test:` adding tests
- `chore:` maintenance tasks

## Code Review

All submissions require review. We use GitHub pull requests for this purpose.
