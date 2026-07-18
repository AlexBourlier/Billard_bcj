import { lazy, Suspense, useEffect } from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { MainLayout } from "../layouts/MainLayout";
import { CookieBanner } from "../components/cookies/CookieBanner";
import { MatomoTracker } from "../components/analytics/MatomoTracker";

// Chargement differe des pages : chaque page devient un fichier JS separe,
// telecharge uniquement lorsque l'utilisateur visite la route correspondante.
// Le bundle initial (page d'accueil) s'en trouve nettement allege.
const HomePage = lazy(() => import("../pages/HomePage").then((m) => ({ default: m.HomePage })));
const DisciplinePage = lazy(() => import("../pages/DisciplinePage").then((m) => ({ default: m.DisciplinePage })));
const NotFoundPage = lazy(() => import("../pages/NotFoundPage").then((m) => ({ default: m.NotFoundPage })));
const PostPage = lazy(() => import("../pages/PostPage").then((m) => ({ default: m.PostPage })));
const ClubPostsPage = lazy(() => import("../pages/ClubPostsPage").then((m) => ({ default: m.ClubPostsPage })));
const CalendarPage = lazy(() => import("../pages/CalendarPage").then((m) => ({ default: m.CalendarPage })));
const ContactPage = lazy(() => import("../pages/ContactPage").then((m) => ({ default: m.ContactPage })));
const ErrorPage = lazy(() => import("../pages/ErrorPage").then((m) => ({ default: m.ErrorPage })));
const CGUPage = lazy(() => import("../pages/CGUPage").then((m) => ({ default: m.CGUPage })));
const MentionsPage = lazy(() => import("../pages/MentionsLegales").then((m) => ({ default: m.MentionsPage })));
const PolitiqueConfidentialitePage = lazy(() => import("../pages/PolitiqueConfidentialite").then((m) => ({ default: m.PolitiqueConfidentialitePage })));
const DisciplineDocumentsPage = lazy(() => import("../components/discipline/DisciplineDocumentsPage").then((m) => ({ default: m.DisciplineDocumentsPage })));
const DisciplineRankingsPage = lazy(() => import("../components/discipline/DisciplineRankingsPage").then((m) => ({ default: m.DisciplineRankingsPage })));
const DisciplineCalendarPage = lazy(() => import("../components/discipline/DisciplineCalendarPage").then((m) => ({ default: m.DisciplineCalendarPage })));
const DisciplinePostsPage = lazy(() => import("../components/discipline/DisciplinePostsPage").then((m) => ({ default: m.DisciplinePostsPage })));

export function AdminRedirect() {
    useEffect(() => {
        window.location.replace("https://api.test.alexandrebourlier.fr/admin");
    }, []);

    return null;
}
    
export function AppRouter() {
    return (
        <BrowserRouter>
            <MatomoTracker />
            <Suspense fallback={<div className="route-loading" aria-busy="true" aria-label="Chargement" />}>
            <Routes>
                <Route
                    path="/"
                    element={
                        <MainLayout>
                            <HomePage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/disciplines/:discipline"
                    element={
                        <MainLayout>
                            <DisciplinePage />
                        </MainLayout>
                    }
                >
                    <Route index element={<DisciplinePostsPage />} />
                    <Route path="articles" element={<DisciplinePostsPage />} />
                    <Route path="calendrier" element={<DisciplineCalendarPage />} />
                    <Route path="classements" element={<DisciplineRankingsPage />} />
                    <Route path="documents" element={<DisciplineDocumentsPage />} />
                </Route>

                <Route
                    path="/posts/:slug"
                    element={
                        <MainLayout>
                            <PostPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/club"
                    element={
                        <MainLayout>
                            <ClubPostsPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/club/annee/:period"
                    element={
                        <MainLayout>
                            <ClubPostsPage />
                        </MainLayout>
                    }
                />

                <Route 
                    path="/calendrier"
                    element={
                        <MainLayout>
                            <CalendarPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/contact"
                    element={
                        <MainLayout>
                            <ContactPage />
                        </MainLayout>
                    }
                />

                <Route 
                    path="/erreur"
                    element={
                        <MainLayout>
                            <ErrorPage />
                        </MainLayout>
                    }
                />
                <Route
                    path="/404"
                    element={
                        <MainLayout>
                            <NotFoundPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="*"
                    element={
                        <MainLayout>
                            <NotFoundPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/cgu"
                    element={
                        <MainLayout>
                            <CGUPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/mentions-legales"
                    element={
                        <MainLayout>
                            <MentionsPage />
                        </MainLayout>
                    }
                />

                <Route
                    path="/politique-confidentialite"
                    element={
                        <MainLayout>
                            <PolitiqueConfidentialitePage />
                        </MainLayout>
                    }
                />

                <Route 
                    path="/admin"
                    element={<AdminRedirect />}
                />
                <Route 
                    path="/login"
                    element={<AdminRedirect />}
                />
            </Routes>
            </Suspense>
            <CookieBanner />
        </BrowserRouter>
    );
}