import { BrowserRouter, Route, Routes } from "react-router-dom";
import { HomePage } from "../pages/HomePage";
import { DisciplinePage } from "../pages/DisciplinePage";
import { NotFoundPage } from "../pages/NotFoundPage";
import { PostPage } from "../pages/PostPage";
import { ClubPostsPage } from "../pages/ClubPostsPage";
import { MainLayout } from "../layouts/MainLayout";

export function AppRouter() {
    return (
        <BrowserRouter>
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
                />

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
                    path="*"
                    element={
                        <MainLayout>
                            <NotFoundPage />
                        </MainLayout>
                    }
                />
            </Routes>
        </BrowserRouter>
    );
}