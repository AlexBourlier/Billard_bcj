import { useState } from "react";
import { Link } from "react-router-dom";

import type { Post } from "../../types/api";
import { Signets } from "../ui/Signets";
import { SeeMore } from "../ui/SeeMore";

type ClubPostCardProps = {
    post: Post;
    activePeriod: string | null;
};

function isYouTubeUrl(url: string) {
    return url.includes("youtube.com") || url.includes("youtu.be");
}

function isSportEnFranceUrl(url: string) {
    return url.includes("sportenfrance.com");
}

function getYouTubeEmbedUrl(url: string) {
    const youtubeWatch = url.match(/youtube\.com\/watch\?v=([^&]+)/)?.[1];
    const youtubeShort = url.match(/youtu\.be\/([^?]+)/)?.[1];
    const youtubeEmbed = url.match(/youtube\.com\/embed\/([^?]+)/)?.[1];
    const youtubeLive = url.match(/youtube\.com\/live\/([^?]+)/)?.[1];

    const youtubeId =
        youtubeWatch ?? youtubeShort ?? youtubeEmbed ?? youtubeLive;

    if (!youtubeId) {
        return null;
    }

    return `https://www.youtube.com/embed/${youtubeId}`;
}

export default function ClubPostCard({
    post,
    activePeriod,
}: ClubPostCardProps) {
    const [isVideoOpen, setIsVideoOpen] = useState(false);

    const title = post.title ?? post.titre ?? "Article sans titre";

    const from = activePeriod
        ? `/club/annee/${activePeriod}`
        : "/club";

    const videoUrl = post.video ?? post.video_url ?? null;

    const isYoutube = videoUrl ? isYouTubeUrl(videoUrl) : false;
    const isSportEnFrance = videoUrl ? isSportEnFranceUrl(videoUrl) : false;

    const youtubeEmbedUrl =
        videoUrl && isYoutube ? getYouTubeEmbedUrl(videoUrl) : null;

    return (
        <article className="club-post-card">
            <div className="club-post-card__media">
                {videoUrl ? (
                    isSportEnFrance ? (
                        <a
                            href={videoUrl}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="club-post-card__video-button"
                            aria-label={`Ouvrir la vidéo : ${title}`}
                        >
                            {post.image_url ? (
                                <img
                                    src={post.image_url}
                                    alt=""
                                    className="club-post-card__image"
                                    loading="lazy"
                                />
                            ) : (
                                <span className="club-post-card__video-placeholder">
                                    Vidéo
                                </span>
                            )}

                            <span
                                className="club-post-card__play"
                                aria-hidden="true"
                            >
                                ▶
                            </span>
                        </a>
                    ) : youtubeEmbedUrl ? (
                        <button
                            type="button"
                            className="club-post-card__video-button"
                            onClick={() => setIsVideoOpen(true)}
                            aria-label={`Lire la vidéo : ${title}`}
                        >
                            {post.image_url ? (
                                <img
                                    src={post.image_url}
                                    alt=""
                                    className="club-post-card__image"
                                    loading="lazy"
                                />
                            ) : (
                                <span className="club-post-card__video-placeholder">
                                    Vidéo
                                </span>
                            )}

                            <span
                                className="club-post-card__play"
                                aria-hidden="true"
                            >
                                ▶
                            </span>
                        </button>
                    ) : (
                        post.image_url && (
                            <img
                                src={post.image_url}
                                alt={title}
                                className="club-post-card__image"
                                loading="lazy"
                            />
                        )
                    )
                ) : (
                    post.image_url && (
                        <img
                            src={post.image_url}
                            alt={title}
                            className="club-post-card__image"
                            loading="lazy"
                        />
                    )
                )}

                {youtubeEmbedUrl && isVideoOpen && (
                    <div
                        className="video-modal"
                        role="dialog"
                        aria-modal="true"
                        aria-label={`Vidéo : ${title}`}
                    >
                        <button
                            type="button"
                            className="video-modal__overlay"
                            onClick={() => setIsVideoOpen(false)}
                            aria-label="Fermer la vidéo"
                        />

                        <div className="video-modal__content">
                            <button
                                type="button"
                                className="video-modal__close"
                                onClick={() => setIsVideoOpen(false)}
                            >
                                Fermer
                            </button>

                            <iframe
                                src={`${youtubeEmbedUrl}?autoplay=1`}
                                title={title}
                                className="video-modal__iframe"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowFullScreen
                            />
                        </div>
                    </div>
                )}
            </div>

            <div className="club-post-card__content">
                <h2 className="club-post-card__title">
                    {post.slug ? (
                        <Link
                            to={`/posts/${post.slug}`}
                            state={{ from }}
                        >
                            {title}
                        </Link>
                    ) : (
                        title
                    )}
                </h2>

                {post.year && (
                    <Signets id={`year-${post.year}`}>
                        {post.year}
                    </Signets>
                )}

                {post.excerpt && (
                    <div
                        className="club-post-card__excerpt"
                        dangerouslySetInnerHTML={{
                            __html: post.excerpt,
                        }}
                    />
                )}

                {post.slug && (
                    <SeeMore id={`see-more-${post.id}`}>
                        <Link
                            to={`/posts/${post.slug}`}
                            state={{ from }}
                        >
                            En voir plus
                        </Link>
                    </SeeMore>
                )}
            </div>
        </article>
    );
}