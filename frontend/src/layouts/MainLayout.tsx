import { Header } from "../components/layout/Header";

export function MainLayout({ children }: { children: React.ReactNode }) {
    return (
        <div className="app-background">
            <div className="app-overlay" aria-hidden="true" />

            <div className="app-shell">
                <Header />

                <main className="main-content">
                    <div className="container">
                        {children}
                    </div>
                </main>

                <footer className="site-footer">
                    <div className="container">© BCJ37</div>
                </footer>
            </div>
        </div>
    );
}