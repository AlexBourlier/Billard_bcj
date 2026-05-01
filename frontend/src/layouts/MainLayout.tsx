export function MainLayout({ children }: { children: React.ReactNode }) {
    return (
        <>
            <header>
                <h1>BCJ37</h1>
            </header>

            <main>{children}</main>

            <footer>© BCJ37</footer>
        </>
    );
}