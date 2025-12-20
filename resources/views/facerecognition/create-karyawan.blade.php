@extends('layouts.mobile.app')
@section('content')
    <style>
        /* Override layout mobile untuk fullscreen */
        body {
            overflow: hidden !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        html,
        body {
            height: 100% !important;
            width: 100% !important;
        }

        .layout-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            height: 100vh !important;
            height: 100dvh !important;
        }

        .layout-content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            height: 100% !important;
        }

        .content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            height: 100% !important;
        }

        /* Tampilkan bottom navigation - jangan sembunyikan */
        .appBottomMenu {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            z-index: 10000 !important;
            position: fixed !important;
            bottom: 0 !important;
        }

        /* Sembunyikan header jika ada */
        .appHeader {
            display: none !important;
        }

        /* Pastikan tidak ada padding bottom dari layout */
        .layout-wrapper,
        .layout-content-wrapper,
        .content-wrapper {
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
        }

        /* Pastikan content section fullscreen */
        #content-section,
        .content-section {
            margin: 0 !important;
            padding: 0 !important;
            height: 100vh !important;
            height: 100dvh !important;
        }

        /* ============================================
                                                                                                                   MODERN MOBILE NATIVE FACE RECOGNITION UI
                                                                                                                   ============================================ */

        /* Fullscreen container - Modern Native Design */
        .webcam-container {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            /* Sampai ke bottom navigation */
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #000;
            overflow: hidden;
            display: block !important;
            /* Ubah dari flex */
            z-index: 9998;
            /* Di bawah bottom nav */
            box-sizing: border-box;
            /* Optimasi untuk Android - hardware acceleration */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            will-change: contents;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

        .webcam-capture {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #000;
            display: block !important;
            box-sizing: border-box;
        }

        .webcam-capture video {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            /* Cover untuk fullscreen tanpa space */
            display: block;
            background: #000;
            margin: 0 !important;
            padding: 0 !important;
            /* Optimasi untuk Android - hardware acceleration */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            will-change: auto;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            /* Optimasi rendering untuk Android */
            -webkit-perspective: 1000;
            perspective: 1000;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }

        /* Pastikan semua element Webcam.js fullscreen */
        .webcam-capture>* {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Pastikan tidak ada space dari parent elements */
        .webcam-container * {
            box-sizing: border-box;
        }

        /* Untuk desktop, gunakan contain */
        @media (min-width: 769px) {
            .webcam-capture video {
                object-fit: contain;
            }
        }

        /* ============================================
                                                                                                                   MODERN STEP INDICATOR - Native Style
                                                                                                                   ============================================ */
        .step-indicator {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 12px 16px;
            padding-top: max(12px, env(safe-area-inset-top));
            z-index: 1002;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 70%, transparent 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            gap: 8px;
            justify-content: center;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .step-indicator::-webkit-scrollbar {
            display: none;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 10px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 44px;
            flex-shrink: 0;
        }

        .step-item.active {
            color: #fff;
        }

        .step-item.completed {
            color: #4ade80;
        }

        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .step-item.active .step-number {
            background: #4ade80;
            border-color: #4ade80;
            box-shadow: 0 4px 16px rgba(74, 222, 128, 0.4);
            transform: scale(1.05);
        }

        .step-item.completed .step-number {
            background: #4ade80;
            border-color: #4ade80;
        }

        .step-item.completed .step-number::before {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        .step-item.completed .step-number {
            color: transparent;
        }

        /* ============================================
                                                                                                                   MODERN FACE GUIDE - Native Style
                                                                                                                   ============================================ */
        .face-guide {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) translateZ(0);
            width: 280px;
            height: 360px;
            max-width: 75vw;
            max-height: 50vh;
            border: 2.5px solid rgba(74, 222, 128, 0.4);
            border-radius: 24px;
            pointer-events: none;
            z-index: 1001;
            transition: border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
            /* Optimasi untuk Android */
            will-change: border-color, box-shadow;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

        .face-guide.ready {
            border-color: #4ade80;
            border-width: 3px;
            box-shadow: 0 0 0 2px rgba(74, 222, 128, 0.2),
                0 0 24px rgba(74, 222, 128, 0.3);
            animation: pulseGuide 2s ease-in-out infinite;
        }

        @keyframes pulseGuide {

            0%,
            100% {
                box-shadow: 0 0 0 2px rgba(74, 222, 128, 0.2),
                    0 0 24px rgba(74, 222, 128, 0.3);
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.15),
                    0 0 32px rgba(74, 222, 128, 0.4);
                transform: translate(-50%, -50%) scale(1.02);
            }
        }

        /* ============================================
                                                                                                               MODERN PROGRESS BAR - Native Style
                                                                                                               ============================================ */
        .progress-container {
            position: absolute;
            bottom: 240px;
            /* Space untuk bottom nav dan button */
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            max-width: 85%;
            background: rgba(0, 0, 0, 0.7);
            padding: 12px 20px;
            border-radius: 16px;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: none;
            z-index: 1001;
        }

        .progress-container.active {
            display: block;
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 8px;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: #4ade80;
            border-radius: 3px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.4),
                    transparent);
            animation: progressShine 1.5s ease-in-out infinite;
        }

        @keyframes progressShine {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .progress-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            letter-spacing: 0.2px;
        }

        /* ============================================
                                                                                                               MODERN GUIDE TEXT - Native Style
                                                                                                               ============================================ */
        .guide-text {
            position: fixed !important;
            /* Ubah ke fixed agar tidak terpengaruh container */
            bottom: 180px !important;
            /* Space untuk bottom nav (70px) + button (60px) + margin (50px) = 180px */
            left: 50%;
            transform: translateX(-50%) translateZ(0);
            text-align: center;
            color: #fff;
            background: rgba(0, 0, 0, 0.75);
            padding: 16px 24px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 15px;
            line-height: 1.5;
            min-width: 280px;
            max-width: 85%;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
            z-index: 10001 !important;
            /* Lebih tinggi dari button (10002) tapi tetap di bawah overlay */
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            word-wrap: break-word;
            /* Optimasi untuk Android */
            will-change: opacity;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

        /* Sembunyikan guide text saat quality overlay aktif */
        .quality-overlay.active~.guide-text,
        .quality-overlay.active~* .guide-text {
            display: none !important;
        }

        /* Atau lebih spesifik: sembunyikan guide text saat quality overlay aktif */
        body:has(.quality-overlay.active) .guide-text {
            display: none !important;
        }

        .guide-text.ready {
            background: rgba(74, 222, 128, 0.9);
            border-color: rgba(74, 222, 128, 0.5);
            box-shadow: 0 8px 32px rgba(74, 222, 128, 0.3);
            animation: guideReady 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes guideReady {
            0% {
                transform: translateX(-50%) scale(0.96);
                opacity: 0.9;
            }

            100% {
                transform: translateX(-50%) scale(1);
                opacity: 1;
            }
        }

        /* ============================================
                                                                                                               MODERN CAPTURE COUNTER - Native Style
                                                                                                               ============================================ */
        .capture-counter {
            position: absolute;
            bottom: 220px;
            /* Space untuk bottom nav dan button */
            left: 50%;
            transform: translateX(-50%);
            background: rgba(99, 102, 241, 0.95);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.3px;
            display: none;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 1001;
            animation: counterPop 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes counterPop {
            0% {
                transform: translateX(-50%) scale(0.9);
                opacity: 0;
            }

            100% {
                transform: translateX(-50%) scale(1);
                opacity: 1;
            }
        }

        .capture-counter.active {
            display: block;
        }

        /* ============================================
                                                                                                                               QUALITY WARNING OVERLAY (Direct on Camera)
                                                                                                                               ============================================ */
        .quality-overlay {
            position: fixed !important;
            /* Ubah ke fixed agar tidak terpengaruh container */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            min-width: 400px;
            max-width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            z-index: 10003 !important;
            /* Lebih tinggi dari guide text (10001) dan button (10002) */
            display: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(239, 68, 68, 0.5);
            animation: qualityOverlaySlide 0.4s ease-out;
        }

        @keyframes qualityOverlaySlide {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        .quality-overlay.active {
            display: block;
        }

        .quality-overlay-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quality-overlay-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #ef4444;
        }

        .quality-overlay-title {
            flex: 1;
            color: white;
            font-size: 20px;
            font-weight: 600;
        }

        .quality-overlay-errors {
            margin-bottom: 20px;
        }

        .quality-error-item {
            background: rgba(239, 68, 68, 0.1);
            border-left: 3px solid #ef4444;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            line-height: 1.5;
        }

        .quality-error-item:last-child {
            margin-bottom: 0;
        }

        .quality-scores {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .quality-scores-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .quality-score-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            color: white;
            font-size: 13px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .quality-score-item:last-child {
            border-bottom: none;
        }

        .quality-score-label {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quality-score-value {
            font-weight: 600;
        }

        .quality-score-value.good {
            color: #22c55e;
        }

        .quality-score-value.warning {
            color: #f59e0b;
        }

        .quality-score-value.bad {
            color: #ef4444;
        }

        .quality-overlay-actions {
            display: flex;
            gap: 10px;
        }

        .quality-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quality-btn-retry {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
        }

        .quality-btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
        }

        .quality-btn-skip {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .quality-btn-skip:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* ============================================
                                                                                                               MODERN START BUTTON - Native Style
                                                                                                               ============================================ */
        .btn-start-overlay {
            position: fixed !important;
            /* Ubah ke fixed agar tidak terpengaruh container */
            bottom: 80px !important;
            /* Space untuk bottom nav (sekitar 70px) + margin (10px) */
            left: 50%;
            transform: translateX(-50%);
            background: #4ade80;
            color: #000;
            border: none;
            padding: 18px 48px;
            border-radius: 28px;
            font-weight: 600;
            font-size: 17px;
            letter-spacing: 0.3px;
            cursor: pointer;
            z-index: 10002 !important;
            /* Di atas bottom nav (10000) */
            box-shadow: 0 4px 16px rgba(74, 222, 128, 0.4),
                0 0 0 0 rgba(74, 222, 128, 0);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 240px;
            max-width: calc(100% - 32px);
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        .btn-start-overlay:active {
            transform: translateX(-50%) scale(0.98);
            box-shadow: 0 2px 8px rgba(74, 222, 128, 0.3);
        }

        .btn-start-overlay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ============================================
                                                                                                                               LOADING OVERLAY (Professional)
                                                                                                                               ============================================ */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 2000;
            backdrop-filter: blur(10px);
        }

        .loading-overlay.active {
            display: flex;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .loading-content {
            text-align: center;
        }

        .loading-content .spinner-border {
            width: 60px;
            height: 60px;
            border-width: 5px;
            border-color: #22c55e;
            border-right-color: transparent;
        }

        /* ============================================
                                                                                                                   MODERN STATUS INDICATOR - Native Style
                                                                                                                   ============================================ */
        .status-indicator {
            position: absolute;
            top: max(12px, env(safe-area-inset-top) + 8px);
            right: 16px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.6);
            z-index: 1001;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .status-indicator.ready {
            background: #4ade80;
            border-color: #4ade80;
            box-shadow: 0 0 12px rgba(74, 222, 128, 0.6);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.15);
                opacity: 0.85;
            }
        }

        /* ============================================
                                                                                                                               RESPONSIVE - MOBILE FIRST
                                                                                                                               ============================================ */
        @media (max-width: 768px) {

            /* Fullscreen untuk mobile - tanpa space */
            .webcam-container {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                height: 100dvh !important;
                margin: 0 !important;
                padding: 0 !important;
                z-index: 9998;
                display: block !important;
            }

            .webcam-capture {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .webcam-capture video {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Step indicator - Modern Native */
            .step-indicator {
                padding: 10px 12px;
                padding-top: max(10px, env(safe-area-inset-top) + 8px);
                gap: 6px;
            }

            .step-item {
                font-size: 9px;
                min-width: 38px;
            }

            .step-number {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }

            /* Face guide - Mobile optimized */
            .face-guide {
                width: 260px;
                height: 340px;
                border-width: 2.5px;
            }

            /* Guide text - Mobile optimized */
            .guide-text {
                bottom: 180px !important;
                /* Space untuk bottom nav dan button */
                min-width: 85%;
                max-width: 90%;
                font-size: 14px;
                padding: 14px 20px;
                line-height: 1.5;
                border-radius: 18px;
            }

            /* Progress container - Mobile */
            .progress-container {
                bottom: 210px;
                /* Space untuk bottom nav dan button */
                width: 85%;
                padding: 10px 16px;
            }

            .progress-text {
                font-size: 11px;
            }

            /* Capture counter - Mobile */
            .capture-counter {
                bottom: 190px;
                /* Space untuk bottom nav dan button */
                padding: 8px 16px;
                font-size: 12px;
                border-radius: 18px;
            }

            /* Start button - Mobile Native */
            .btn-start-overlay {
                bottom: 80px !important;
                /* Space untuk bottom nav (70px) + margin (10px) */
                padding: 16px 40px;
                font-size: 16px;
                min-width: 85%;
                max-width: calc(100% - 32px);
                border-radius: 26px;
                z-index: 10002 !important;
                /* Di atas bottom nav */
            }

            /* Quality overlay untuk mobile */
            .quality-overlay {
                min-width: 90%;
                max-width: 95%;
                padding: 20px;
                max-height: 80vh;
                overflow-y: auto;
            }

            .quality-overlay-title {
                font-size: 18px;
            }

            .quality-error-item {
                font-size: 13px;
                padding: 10px 12px;
            }

            /* Status indicator lebih kecil */
            .status-indicator {
                top: 10px;
                right: 10px;
                width: 12px;
                height: 12px;
            }

            /* Loading overlay */
            .loading-overlay {
                font-size: 14px;
            }

            .loading-content .spinner-border {
                width: 50px;
                height: 50px;
            }
        }

        /* Extra small devices */
        @media (max-width: 480px) {
            .step-indicator {
                gap: 6px;
                padding: 6px 10px;
            }

            .step-item {
                font-size: 9px;
                min-width: 35px;
            }

            .step-number {
                width: 24px;
                height: 24px;
                font-size: 11px;
            }

            .face-guide {
                width: 240px;
                height: 320px;
            }

            .guide-text {
                bottom: 180px !important;
                font-size: 15px;
                padding: 14px 18px;
            }

            .progress-container {
                bottom: 150px;
                padding: 10px 14px;
            }

            .btn-start-overlay {
                bottom: 75px !important;
                /* Space untuk bottom nav */
                padding: 14px 28px;
                font-size: 15px;
                z-index: 10002 !important;
            }

            .capture-counter {
                bottom: 130px;
                padding: 8px 16px;
                font-size: 12px;
            }
        }

        /* Landscape orientation untuk mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .step-indicator {
                top: 5px;
                padding: 6px 10px;
            }

            .face-guide {
                width: 200px;
                height: 280px;
            }

            .guide-text {
                bottom: 180px !important;
                font-size: 14px;
            }

            .progress-container {
                bottom: 120px;
            }

            .btn-start-overlay {
                bottom: 75px !important;
                /* Space untuk bottom nav */
                padding: 12px 24px;
                font-size: 14px;
                z-index: 10002 !important;
            }
        }
    </style>

    <!-- Fullscreen Professional Face Recognition Interface -->
    <div class="webcam-container" id="webcamContainer">
        <div class="webcam-capture" id="webcamCapture"></div>

        <!-- Step Indicator -->
        <div class="step-indicator" id="stepIndicator">
            <div class="step-item" data-step="1">
                <div class="step-number">1</div>
                <span>Posisi</span>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-number">2</div>
                <span>Depan</span>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-number">3</div>
                <span>Kiri</span>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-number">4</div>
                <span>Kanan</span>
            </div>
            <div class="step-item" data-step="5">
                <div class="step-number">5</div>
                <span>Atas</span>
            </div>
            <div class="step-item" data-step="6">
                <div class="step-number">6</div>
                <span>Bawah</span>
            </div>
        </div>

        <!-- Status Indicator -->
        <div class="status-indicator" id="statusIndicator"></div>

        <!-- Face Guide -->
        <div class="face-guide" id="faceGuide"></div>

        <!-- Progress Container -->
        <div class="progress-container" id="progressContainer">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-text" id="progressText">Stabilitas: 0%</div>
        </div>

        <!-- Guide Text -->
        <div class="guide-text" id="guideText">
            Klik tombol di bawah untuk mulai merekam wajah
        </div>

        <!-- Capture Counter -->
        <div class="capture-counter" id="captureCounter">
            <i class="ti ti-camera me-2"></i>
            <span id="captureText">Mengambil foto...</span>
        </div>

        <!-- Start Button -->
        <button class="btn-start-overlay" id="btnMulaiRekam">
            <i class="ti ti-video me-2"></i>Mulai Rekam Wajah
        </button>

        <!-- Quality Warning Overlay -->
        <div class="quality-overlay" id="qualityOverlay">
            <div class="quality-overlay-header">
                <div class="quality-overlay-icon">
                    <i class="ti ti-alert-triangle"></i>
                </div>
                <div class="quality-overlay-title">Kualitas Gambar Tidak Memenuhi</div>
            </div>

            <div class="quality-overlay-errors" id="qualityErrors">
                <!-- Error messages akan diisi oleh JavaScript -->
            </div>

            <div class="quality-scores" id="qualityScores">
                <div class="quality-scores-title">Detail Kualitas</div>
                <div id="qualityScoresList">
                    <!-- Scores akan diisi oleh JavaScript -->
                </div>
            </div>

            <div class="quality-overlay-actions">
                <button class="quality-btn quality-btn-retry" id="qualityBtnRetry">
                    <i class="ti ti-refresh me-2"></i>Coba Lagi
                </button>
                <button class="quality-btn quality-btn-skip" id="qualityBtnSkip">
                    <i class="ti ti-check me-2"></i>Tetap Simpan
                </button>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-content">
                <div class="spinner-border text-light mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="h4 mb-2">Memproses foto...</div>
                <div class="text-muted">Mohon tunggu sebentar</div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script>
        // Fungsi untuk memuat face-api.js
        function loadFaceApiScript() {
            return new Promise((resolve, reject) => {
                if (typeof faceapi !== 'undefined') {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        // Fungsi untuk memuat model face-api
        async function loadFaceApiModels() {
            try {
                const isAndroid = /Android/i.test(navigator.userAgent);

                // Untuk Android: gunakan TinyFaceDetector (lebih ringan)
                // Untuk Desktop/iOS: tetap pakai SSD Mobilenet (lebih akurat)
                if (isAndroid) {
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                        faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                    ]);
                    console.log('Face API models loaded (TinyFaceDetector for Android)');
                } else {
                    await Promise.all([
                        faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                        faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                    ]);
                    console.log('Face API models loaded (SSD Mobilenet for Desktop/iOS)');
                }
                return true;
            } catch (error) {
                console.error('Error loading face-api models:', error);
                return false;
            }
        }

        // Variabel untuk tracking retry
        let startVideoRetryCount = 0;
        const MAX_START_VIDEO_RETRIES = 10; // Maksimal 10 kali retry

        // Fungsi untuk memulai video
        function startVideo() {
            // Pastikan elemen ada sebelum attach
            const webcamElement = document.querySelector('.webcam-capture') || document.getElementById('webcamCapture');
            if (!webcamElement) {
                startVideoRetryCount++;
                if (startVideoRetryCount < MAX_START_VIDEO_RETRIES) {
                    console.warn(`Webcam element not found! Retrying... (${startVideoRetryCount}/${MAX_START_VIDEO_RETRIES})`);
                    setTimeout(startVideo, 1000); // Retry setelah 1 detik
                } else {
                    console.error('Webcam element not found after maximum retries. Please refresh the page.');
                }
                return;
            }

            // Reset retry count jika elemen ditemukan
            startVideoRetryCount = 0;

            // Pastikan elemen visible dan memiliki dimensi
            if (webcamElement.offsetWidth === 0 || webcamElement.offsetHeight === 0) {
                console.warn('Webcam element has no dimensions, waiting...');
                setTimeout(startVideo, 1000);
                return;
            }

            console.log('Webcam element found:', webcamElement);
            console.log('Element dimensions:', webcamElement.offsetWidth, 'x', webcamElement.offsetHeight);

            // Deteksi device untuk optimasi Android
            const isAndroid = /Android/i.test(navigator.userAgent);
            const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

            // Optimasi resolusi dan frame rate untuk Android
            const videoConfig = isAndroid ? {
                width: 640,
                height: 480,
                idealWidth: 640,
                idealHeight: 480,
                minWidth: 320,
                minHeight: 240,
                idealFrameRate: 15,
                minFrameRate: 10
            } : isMobile ? {
                width: 960,
                height: 720,
                idealWidth: 960,
                idealHeight: 720,
                minWidth: 640,
                minHeight: 480,
                idealFrameRate: 24,
                minFrameRate: 15
            } : {
                width: 1280,
                height: 720,
                idealWidth: 1280,
                idealHeight: 720,
                minWidth: 640,
                minHeight: 480,
                idealFrameRate: 30,
                minFrameRate: 24
            };

            Webcam.set({
                width: videoConfig.width,
                height: videoConfig.height,
                image_format: 'jpeg',
                jpeg_quality: isAndroid ? 85 : 95, // Kurangi kualitas untuk Android
                fps: videoConfig.idealFrameRate,
                constraints: {
                    video: {
                        facingMode: "user",
                        width: {
                            ideal: videoConfig.idealWidth,
                            min: videoConfig.minWidth
                        },
                        height: {
                            ideal: videoConfig.idealHeight,
                            min: videoConfig.minHeight
                        },
                        frameRate: {
                            ideal: videoConfig.idealFrameRate,
                            min: videoConfig.minFrameRate
                        }
                    }
                }
            });

            try {
                // Detach dulu jika sudah ada
                if (Webcam.live) {
                    Webcam.reset();
                }

                Webcam.attach(webcamElement);
                console.log('Webcam attached successfully to element');
            } catch (error) {
                console.error('Error attaching webcam:', error);
                // Retry setelah 500ms
                setTimeout(startVideo, 500);
            }

            // Update container dan info setelah video ready
            setTimeout(() => {
                const video = document.querySelector('.webcam-capture video');
                if (video && video.videoWidth > 0 && video.videoHeight > 0) {
                    const actualWidth = video.videoWidth;
                    const actualHeight = video.videoHeight;
                    const actualAspectRatio = actualWidth / actualHeight;

                    console.log('Webcam started successfully');
                    console.log(`Camera native resolution: ${actualWidth}x${actualHeight}`);
                    console.log(`Camera native aspect ratio: ${actualAspectRatio.toFixed(2)}:1`);

                    // Simpan resolusi video untuk threshold dinamis
                    videoWidth = actualWidth;
                    videoHeight = actualHeight;

                    // Hitung threshold berdasarkan resolusi
                    const thresholds = getFaceSizeThresholds();
                    console.log('Face size thresholds (dynamic):', thresholds);
                    console.log(`Video resolution: ${actualWidth}x${actualHeight}`);
                    console.log(`Min face width: ${thresholds.minWidth}px (${(thresholds.minWidth/actualWidth*100).toFixed(1)}%)`);
                    console.log(`Max face width: ${thresholds.maxWidth}px (${(thresholds.maxWidth/actualWidth*100).toFixed(1)}%)`);

                    // Untuk mobile, tidak perlu adjust container - biarkan CSS handle fullscreen
                    const isMobile = window.innerWidth <= 768;
                    const container = document.querySelector('.webcam-container');
                    const webcamCapture = document.querySelector('.webcam-capture');

                    if (isMobile && container && webcamCapture) {
                        // Mobile: pastikan container dan capture benar-benar fullscreen
                        container.style.width = '100vw';
                        container.style.height = '100vh';
                        container.style.height = '100dvh';
                        container.style.top = '0';
                        container.style.left = '0';
                        container.style.right = '0';
                        container.style.bottom = '0';
                        container.style.margin = '0';
                        container.style.padding = '0';
                        container.style.display = 'block';

                        webcamCapture.style.position = 'absolute';
                        webcamCapture.style.top = '0';
                        webcamCapture.style.left = '0';
                        webcamCapture.style.right = '0';
                        webcamCapture.style.bottom = '0';
                        webcamCapture.style.width = '100%';
                        webcamCapture.style.height = '100%';
                        webcamCapture.style.margin = '0';
                        webcamCapture.style.padding = '0';

                        const videoElement = webcamCapture.querySelector('video');
                        if (videoElement) {
                            videoElement.style.position = 'absolute';
                            videoElement.style.top = '0';
                            videoElement.style.left = '0';
                            videoElement.style.width = '100%';
                            videoElement.style.height = '100%';
                            videoElement.style.objectFit = 'cover';
                            videoElement.style.margin = '0';
                            videoElement.style.padding = '0';
                        }

                        console.log('Mobile: Container set to fullscreen without space');
                    } else if (!isMobile && container && webcamCapture) {
                        // Desktop: adjust sesuai aspect ratio
                        webcamCapture.style.aspectRatio = `${actualWidth} / ${actualHeight}`;

                        const viewportWidth = window.innerWidth;
                        const viewportHeight = window.innerHeight;
                        const viewportAspectRatio = viewportWidth / viewportHeight;

                        if (viewportAspectRatio > actualAspectRatio) {
                            container.style.height = '100vh';
                            container.style.width = `${100 * actualAspectRatio / viewportAspectRatio}vw`;
                            container.style.marginLeft = 'auto';
                            container.style.marginRight = 'auto';
                        } else {
                            container.style.width = '100vw';
                            container.style.height = `${100 * viewportAspectRatio / actualAspectRatio}vh`;
                            container.style.marginTop = 'auto';
                            container.style.marginBottom = 'auto';
                        }

                        console.log(`Desktop: Container adjusted to fullscreen with camera aspect ratio: ${actualAspectRatio.toFixed(2)}:1`);
                    }
                }
            }, 1000);
        }

        // ============================================
        // MULTI-CAPTURE LOGIC
        // ============================================
        const DIRECTIONS = [{
                key: 'front',
                label: 'Lurus ke depan',
                step: 2
            },
            {
                key: 'left',
                label: 'Tengok ke kiri',
                step: 3
            },
            {
                key: 'right',
                label: 'Tengok ke kanan',
                step: 4
            },
            {
                key: 'up',
                label: 'Tengok ke atas',
                step: 5
            },
            {
                key: 'down',
                label: 'Tengok ke bawah',
                step: 6
            }
        ];
        const IMAGES_PER_DIRECTION = 1;
        const TOTAL_IMAGES = 5;
        let capturedImages = [];
        let currentDirectionIndex = 0;
        let currentImageInDirection = 0;
        let isMultiCaptureActive = false;

        // Update step indicator
        function updateStepIndicator(step) {
            const stepItems = document.querySelectorAll('.step-item');
            stepItems.forEach((item, index) => {
                const itemStep = parseInt(item.dataset.step);
                item.classList.remove('active', 'completed');

                if (itemStep < step) {
                    item.classList.add('completed');
                } else if (itemStep === step) {
                    item.classList.add('active');
                }
            });
        }

        function showDirectionInstruction() {
            const guideText = document.getElementById('guideText');
            if (currentDirectionIndex < DIRECTIONS.length) {
                const direction = DIRECTIONS[currentDirectionIndex];
                updateStepIndicator(direction.step);

                if (guideText) {
                    guideText.textContent = `📸 ${direction.label} (${currentImageInDirection + 1}/${IMAGES_PER_DIRECTION})`;
                    guideText.classList.remove('ready');
                }
            } else {
                if (guideText) {
                    guideText.textContent = '✅ Selesai! Menyimpan gambar...';
                    guideText.classList.add('ready');
                }
            }
        }

        function startMultiCapture() {
            capturedImages = [];
            currentDirectionIndex = 0;
            currentImageInDirection = 0;
            isMultiCaptureActive = true;

            // Reset face descriptors untuk session baru
            previousFaceDescriptors = [];

            // Sembunyikan tombol start
            const btnStart = document.getElementById('btnMulaiRekam');
            if (btnStart) {
                btnStart.style.display = 'none';
            }

            // Update step indicator
            updateStepIndicator(1);
            showDirectionInstruction();
        }

        function stopMultiCapture() {
            isMultiCaptureActive = false;
            showDirectionInstruction();
        }

        // ============================================
        // QUALITY VALIDATION FUNCTIONS
        // ============================================

        /**
         * Deteksi blur menggunakan Laplacian variance (Optimized)
         * @param {ImageData} imageData - Image data dari canvas
         * @returns {number} - Blur score (semakin tinggi = semakin sharp)
         */
        function detectBlur(imageData) {
            const width = imageData.width;
            const height = imageData.height;
            const data = imageData.data;

            // Optimasi: Sample setiap 2 pixel untuk performa lebih cepat
            const step = 2;
            let laplacianSum = 0;
            let laplacianSquaredSum = 0;
            let pixelCount = 0;

            for (let y = 1; y < height - 1; y += step) {
                for (let x = 1; x < width - 1; x += step) {
                    const idx = (y * width + x) * 4;

                    // Ambil nilai grayscale
                    const center = (data[idx] + data[idx + 1] + data[idx + 2]) / 3;
                    const right = (data[idx + 4] + data[idx + 5] + data[idx + 6]) / 3;
                    const left = (data[idx - 4] + data[idx - 3] + data[idx - 2]) / 3;
                    const bottom = (data[idx + width * 4] + data[idx + width * 4 + 1] + data[idx + width * 4 + 2]) / 3;
                    const top = (data[idx - width * 4] + data[idx - width * 4 + 1] + data[idx - width * 4 + 2]) / 3;

                    // Hitung Laplacian (second derivative) - lebih akurat
                    const laplacian = Math.abs(center * 4 - (left + right + top + bottom));

                    laplacianSum += laplacian;
                    laplacianSquaredSum += laplacian * laplacian;
                    pixelCount++;
                }
            }

            if (pixelCount === 0) return 0;

            // Hitung variance dalam satu pass (Welford's algorithm simplified)
            const mean = laplacianSum / pixelCount;
            const meanSquared = laplacianSquaredSum / pixelCount;
            const variance = meanSquared - (mean * mean);

            return Math.max(0, variance); // Pastikan non-negative
        }

        /**
         * Validasi exposure (pencahayaan)
         * @param {ImageData} imageData - Image data dari canvas
         * @returns {Object} - {isValid: boolean, brightness: number, message: string}
         */
        function checkExposure(imageData) {
            const data = imageData.data;
            let totalBrightness = 0;
            let pixelCount = 0;

            // Hitung rata-rata brightness (gunakan grayscale)
            for (let i = 0; i < data.length; i += 4) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                const brightness = (r + g + b) / 3;
                totalBrightness += brightness;
                pixelCount++;
            }

            const avgBrightness = totalBrightness / pixelCount;

            // Threshold: 80-220 (0-255 scale)
            const minBrightness = 80;
            const maxBrightness = 220;

            if (avgBrightness < minBrightness) {
                return {
                    isValid: false,
                    brightness: avgBrightness,
                    message: 'Pencahayaan terlalu gelap. Pastikan area terang atau gunakan lampu.'
                };
            } else if (avgBrightness > maxBrightness) {
                return {
                    isValid: false,
                    brightness: avgBrightness,
                    message: 'Pencahayaan terlalu terang. Kurangi cahaya atau pindah ke area lebih gelap.'
                };
            }

            return {
                isValid: true,
                brightness: avgBrightness,
                message: 'Pencahayaan optimal'
            };
        }

        /**
         * Validasi sharpness menggunakan edge detection
         * @param {ImageData} imageData - Image data dari canvas
         * @param {Object} faceBox - Face detection box
         * @returns {Object} - {isValid: boolean, sharpness: number, message: string}
         */
        function checkSharpness(imageData, faceBox) {
            if (!faceBox) {
                return {
                    isValid: false,
                    sharpness: 0,
                    message: 'Wajah tidak terdeteksi'
                };
            }

            // Fokus ke area wajah saja (lebih akurat)
            const width = imageData.width;
            const height = imageData.height;
            const data = imageData.data;

            // Crop area wajah dengan padding
            const padding = 20;
            const startX = Math.max(0, Math.floor(faceBox.x) - padding);
            const startY = Math.max(0, Math.floor(faceBox.y) - padding);
            const endX = Math.min(width, Math.floor(faceBox.x + faceBox.width) + padding);
            const endY = Math.min(height, Math.floor(faceBox.y + faceBox.height) + padding);

            let edgeSum = 0;
            let edgeCount = 0;

            // Hitung edge strength di area wajah menggunakan Sobel operator
            for (let y = startY + 1; y < endY - 1; y++) {
                for (let x = startX + 1; x < endX - 1; x++) {
                    const idx = (y * width + x) * 4;

                    // Grayscale
                    const center = (data[idx] + data[idx + 1] + data[idx + 2]) / 3;
                    const right = (data[idx + 4] + data[idx + 5] + data[idx + 6]) / 3;
                    const bottom = (data[(y + 1) * width * 4 + x * 4] +
                        data[(y + 1) * width * 4 + x * 4 + 1] +
                        data[(y + 1) * width * 4 + x * 4 + 2]) / 3;

                    // Sobel edge detection (simplified)
                    const gx = right - center;
                    const gy = bottom - center;
                    const edge = Math.sqrt(gx * gx + gy * gy);

                    edgeSum += edge;
                    edgeCount++;
                }
            }

            const avgSharpness = edgeSum / edgeCount;
            // Threshold lebih fleksibel untuk webcam (diperlonggar dari 15 ke 10)
            const threshold = 10; // Threshold untuk sharpness

            if (avgSharpness < threshold) {
                return {
                    isValid: false,
                    sharpness: avgSharpness,
                    message: 'Gambar tidak cukup tajam. Pastikan kamera stabil dan fokus ke wajah.'
                };
            }

            return {
                isValid: true,
                sharpness: avgSharpness,
                message: 'Ketajaman optimal'
            };
        }

        /**
         * Validasi kualitas gambar lengkap
         * @param {string} imageUri - Base64 image URI
         * @param {Object} faceBox - Face detection box (optional)
         * @returns {Promise<Object>} - {isValid: boolean, errors: Array, warnings: Array}
         */
        async function validateImageQuality(imageUri, faceBox = null) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);

                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

                    const errors = [];
                    const warnings = [];

                    // 1. Deteksi Blur
                    const blurScore = detectBlur(imageData);
                    // Threshold lebih fleksibel untuk webcam (bisa adjust sesuai kebutuhan)
                    // Untuk webcam: 50-70, untuk mobile: 100
                    const blurThreshold = 70; // Diperlonggar dari 100 ke 70 untuk webcam
                    if (blurScore < blurThreshold) {
                        errors.push({
                            type: 'blur',
                            message: `Gambar terlalu kabur (score: ${blurScore.toFixed(1)}, minimal: ${blurThreshold}). Pastikan kamera stabil, fokus ke wajah, dan pencahayaan cukup.`,
                            score: blurScore
                        });
                    } else {
                        warnings.push(`Blur score: ${blurScore.toFixed(1)} (threshold: ${blurThreshold})`);
                    }

                    // 2. Validasi Exposure
                    const exposureCheck = checkExposure(imageData);
                    if (!exposureCheck.isValid) {
                        errors.push({
                            type: 'exposure',
                            message: `${exposureCheck.message} (brightness: ${exposureCheck.brightness.toFixed(1)}). Pastikan pencahayaan cukup dan merata.`,
                            brightness: exposureCheck.brightness
                        });
                    } else {
                        warnings.push(`Brightness: ${exposureCheck.brightness.toFixed(1)} (optimal)`);
                    }

                    // 3. Validasi Sharpness (jika ada faceBox) - Hanya info, tidak reject
                    let sharpnessScore = null;
                    if (faceBox) {
                        const sharpnessCheck = checkSharpness(imageData, faceBox);
                        sharpnessScore = sharpnessCheck.sharpness;
                        // Sharpness hanya sebagai informasi, tidak reject gambar
                        warnings.push(
                            `Sharpness: ${sharpnessCheck.sharpness.toFixed(1)} ${sharpnessCheck.sharpness >= 10 ? '(optimal)' : '(kurang tajam)'}`
                        );
                    }

                    resolve({
                        isValid: errors.length === 0,
                        errors: errors,
                        warnings: warnings,
                        scores: {
                            blur: blurScore,
                            brightness: exposureCheck.brightness,
                            sharpness: sharpnessScore
                        }
                    });
                };
                img.onerror = function() {
                    resolve({
                        isValid: false,
                        errors: [{
                            type: 'load',
                            message: 'Gagal memuat gambar untuk validasi'
                        }],
                        warnings: [],
                        scores: null
                    });
                };
                img.src = imageUri;
            });
        }

        // Fungsi untuk mengambil foto dengan validasi kualitas
        async function capturePhoto() {
            if (isProcessing || !isFaceDetected) return;
            if (!isMultiCaptureActive) return;

            const currentTime = Date.now();
            if (currentTime - lastCaptureTime < 400) {
                return;
            }

            isProcessing = true;
            lastCaptureTime = currentTime;
            const loadingOverlay = document.getElementById('loadingOverlay');
            const captureCounter = document.getElementById('captureCounter');
            const captureText = document.getElementById('captureText');

            loadingOverlay.classList.add('active');
            captureCounter.classList.add('active');
            captureText.textContent = `Mengambil foto ${capturedImages.length + 1}/${TOTAL_IMAGES}: ${DIRECTIONS[currentDirectionIndex].label}`;

            Webcam.snap(async function(uri) {
                try {
                    // Dapatkan face box untuk validasi sharpness
                    const video = document.querySelector('.webcam-capture video');
                    let faceBox = null;
                    if (video && isModelsLoaded) {
                        try {
                            const isAndroid = /Android/i.test(navigator.userAgent);
                            let detection;
                            if (isAndroid) {
                                detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                                    inputSize: 224,
                                    scoreThreshold: 0.3
                                })).withFaceLandmarks();
                            } else {
                                detection = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options({
                                    minConfidence: 0.5
                                })).withFaceLandmarks();
                            }
                            if (detection) {
                                faceBox = detection.detection.box;
                            }
                        } catch (e) {
                            console.warn('Tidak bisa deteksi wajah untuk validasi:', e);
                        }
                    }

                    // Validasi kualitas gambar
                    captureText.textContent = 'Memvalidasi kualitas gambar...';
                    const qualityCheck = await validateImageQuality(uri, faceBox);

                    if (!qualityCheck.isValid) {
                        // Tampilkan overlay kualitas langsung di kamera
                        showQualityOverlay(qualityCheck, uri);
                        isProcessing = false;
                        return; // Reject foto (kecuali user pilih "Tetap Simpan")
                    }

                    // Validasi konsistensi wajah (Phase 2)
                    captureText.textContent = 'Memvalidasi konsistensi wajah...';
                    const consistencyCheck = await validateFaceConsistency(uri);

                    if (!consistencyCheck.isValid) {
                        // Gabungkan error consistency dengan quality errors
                        const combinedErrors = [
                            ...qualityCheck.errors || [],
                            ...consistencyCheck.errors
                        ];

                        showQualityOverlay({
                            isValid: false,
                            errors: combinedErrors,
                            scores: qualityCheck.scores
                        }, uri);
                        isProcessing = false;
                        return; // Reject foto (kecuali user pilih "Tetap Simpan")
                    }

                    // Jika semua valid, simpan foto
                    // Note: Descriptor sudah disimpan di validateFaceConsistency jika valid
                    proceedWithSave(uri, qualityCheck.scores, false);
                } catch (error) {
                    console.error('Error dalam validasi kualitas:', error);
                    captureCounter.classList.remove('active');
                    loadingOverlay.classList.remove('active');

                    // Tampilkan error di overlay juga
                    showQualityOverlay({
                        isValid: false,
                        errors: [{
                            type: 'error',
                            message: 'Terjadi kesalahan saat memvalidasi gambar. Silakan coba lagi.'
                        }],
                        scores: null
                    }, uri);
                } finally {
                    isProcessing = false;
                }
            });

            // Timeout fallback
            setTimeout(() => {
                if (isProcessing) {
                    isProcessing = false;
                    loadingOverlay.classList.remove('active');
                    captureCounter.classList.remove('active');
                }
            }, 5000);
        }

        // ============================================
        // QUALITY OVERLAY FUNCTIONS (Global Scope)
        // ============================================

        // Variabel global untuk menyimpan rejected image
        let currentRejectedImageUri = null;
        let currentRejectedImageScores = null;

        // ============================================
        // FACE CONSISTENCY VALIDATION (Phase 2)
        // ============================================

        // Variabel untuk menyimpan face descriptors dari foto sebelumnya
        let previousFaceDescriptors = [];
        const FACE_CONSISTENCY_THRESHOLD = 0.6; // Distance threshold (semakin kecil = lebih strict)

        // ============================================
        // FACE CROPPING (Phase 3 - Preprocessing)
        // ============================================

        /**
         * Crop wajah dari gambar dengan padding
         * @param {string} imageUri - Base64 image URI
         * @param {Object} faceBox - Face detection box {x, y, width, height}
         * @param {number} paddingPercent - Padding dalam persen (default: 20%)
         * @param {number} minSize - Ukuran minimum untuk crop (default: 224x224)
         * @returns {Promise<string>} - Base64 cropped image URI
         */
        async function cropFaceFromImage(imageUri, faceBox, paddingPercent = 20, minSize = 224) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = function() {
                    try {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Hitung padding dalam pixel
                        const paddingX = (faceBox.width * paddingPercent) / 100;
                        const paddingY = (faceBox.height * paddingPercent) / 100;

                        // Hitung area crop dengan padding
                        let cropX = Math.max(0, faceBox.x - paddingX);
                        let cropY = Math.max(0, faceBox.y - paddingY);
                        let cropWidth = Math.min(img.width - cropX, faceBox.width + (paddingX * 2));
                        let cropHeight = Math.min(img.height - cropY, faceBox.height + (paddingY * 2));

                        // Pastikan ukuran minimum
                        if (cropWidth < minSize) {
                            const diff = minSize - cropWidth;
                            cropX = Math.max(0, cropX - diff / 2);
                            cropWidth = minSize;
                        }
                        if (cropHeight < minSize) {
                            const diff = minSize - cropHeight;
                            cropY = Math.max(0, cropY - diff / 2);
                            cropHeight = minSize;
                        }

                        // Pastikan tidak melebihi batas gambar
                        if (cropX + cropWidth > img.width) {
                            cropWidth = img.width - cropX;
                        }
                        if (cropY + cropHeight > img.height) {
                            cropHeight = img.height - cropY;
                        }

                        // Set canvas size
                        canvas.width = cropWidth;
                        canvas.height = cropHeight;

                        // Draw cropped image
                        ctx.drawImage(
                            img,
                            cropX, cropY, cropWidth, cropHeight, // Source
                            0, 0, cropWidth, cropHeight // Destination
                        );

                        // Convert ke base64
                        const croppedUri = canvas.toDataURL('image/jpeg', 0.95);
                        resolve(croppedUri);
                    } catch (error) {
                        console.error('Error cropping face:', error);
                        reject(error);
                    }
                };
                img.onerror = function() {
                    reject(new Error('Failed to load image for cropping'));
                };
                img.src = imageUri;
            });
        }

        /**
         * Mendapatkan face box dari image URI
         * @param {string} imageUri - Base64 image URI
         * @returns {Promise<Object|null>} - Face box {x, y, width, height} atau null
         */
        async function getFaceBoxFromImage(imageUri) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = async function() {
                    try {
                        if (!isModelsLoaded) {
                            console.warn('Face API models belum dimuat');
                            resolve(null);
                            return;
                        }

                        // Deteksi wajah
                        const isAndroid = /Android/i.test(navigator.userAgent);
                        let detection;
                        if (isAndroid) {
                            detection = await faceapi
                                .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({
                                    inputSize: 224,
                                    scoreThreshold: 0.3
                                }))
                                .withFaceLandmarks();
                        } else {
                            detection = await faceapi
                                .detectSingleFace(img, new faceapi.SsdMobilenetv1Options({
                                    minConfidence: 0.5
                                }))
                                .withFaceLandmarks();
                        }

                        if (detection && detection.detection) {
                            resolve(detection.detection.box);
                        } else {
                            resolve(null);
                        }
                    } catch (error) {
                        console.error('Error detecting face for cropping:', error);
                        resolve(null);
                    }
                };
                img.onerror = function() {
                    resolve(null);
                };
                img.src = imageUri;
            });
        }

        /**
         * Mendapatkan face descriptor dari image URI
         * @param {string} imageUri - Base64 image URI
         * @returns {Promise<Float32Array|null>} - Face descriptor atau null jika tidak ada wajah
         */
        async function getFaceDescriptor(imageUri) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = async function() {
                    try {
                        if (!isModelsLoaded) {
                            console.warn('Face API models belum dimuat');
                            resolve(null);
                            return;
                        }

                        // Deteksi wajah dan dapatkan descriptor
                        const isAndroid = /Android/i.test(navigator.userAgent);
                        let detection;
                        if (isAndroid) {
                            detection = await faceapi
                                .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({
                                    inputSize: 224,
                                    scoreThreshold: 0.3
                                }))
                                .withFaceLandmarks()
                                .withFaceDescriptor();
                        } else {
                            detection = await faceapi
                                .detectSingleFace(img, new faceapi.SsdMobilenetv1Options({
                                    minConfidence: 0.5
                                }))
                                .withFaceLandmarks()
                                .withFaceDescriptor();
                        }

                        if (detection && detection.descriptor) {
                            resolve(detection.descriptor);
                        } else {
                            console.warn('Tidak ada wajah terdeteksi untuk consistency check');
                            resolve(null);
                        }
                    } catch (error) {
                        console.error('Error mendapatkan face descriptor:', error);
                        resolve(null);
                    }
                };
                img.onerror = function() {
                    console.error('Error memuat gambar untuk consistency check');
                    resolve(null);
                };
                img.src = imageUri;
            });
        }

        /**
         * Membandingkan face descriptor baru dengan descriptor sebelumnya
         * @param {Float32Array} newDescriptor - Face descriptor dari foto baru
         * @param {Array<Float32Array>} previousDescriptors - Array face descriptors dari foto sebelumnya
         * @returns {Object} - {isConsistent: boolean, maxDistance: number, message: string}
         */
        function compareFaceDescriptors(newDescriptor, previousDescriptors) {
            if (!newDescriptor || previousDescriptors.length === 0) {
                return {
                    isConsistent: true, // Skip validation jika tidak ada data
                    maxDistance: 0,
                    message: 'Tidak ada data untuk dibandingkan'
                };
            }

            let maxDistance = 0;
            let minDistance = Infinity;

            // Bandingkan dengan semua foto sebelumnya
            previousDescriptors.forEach((prevDescriptor, index) => {
                if (prevDescriptor) {
                    const distance = faceapi.euclideanDistance(newDescriptor, prevDescriptor);
                    maxDistance = Math.max(maxDistance, distance);
                    minDistance = Math.min(minDistance, distance);
                    console.log(`Distance dengan foto ${index + 1}: ${distance.toFixed(3)}`);
                }
            });

            const isConsistent = maxDistance <= FACE_CONSISTENCY_THRESHOLD;

            return {
                isConsistent: isConsistent,
                maxDistance: maxDistance,
                minDistance: minDistance,
                message: isConsistent ?
                    `Wajah konsisten (distance: ${maxDistance.toFixed(3)})` :
                    `Wajah tidak konsisten (distance: ${maxDistance.toFixed(3)}, maksimal: ${FACE_CONSISTENCY_THRESHOLD}). Pastikan semua foto adalah wajah yang sama.`
            };
        }

        /**
         * Validasi konsistensi wajah
         * @param {string} imageUri - Base64 image URI
         * @returns {Promise<Object>} - {isValid: boolean, errors: Array, warnings: Array}
         */
        async function validateFaceConsistency(imageUri) {
            const errors = [];
            const warnings = [];

            // Dapatkan face descriptor dari foto baru
            const newDescriptor = await getFaceDescriptor(imageUri);

            if (!newDescriptor) {
                // Jika tidak ada wajah terdeteksi, skip validation (sudah di-handle di quality check)
                return {
                    isValid: true,
                    errors: [],
                    warnings: ['Tidak bisa memvalidasi konsistensi (wajah tidak terdeteksi)']
                };
            }

            // Jika ini foto pertama, langsung accept dan simpan descriptor
            if (previousFaceDescriptors.length === 0) {
                previousFaceDescriptors.push(newDescriptor);
                warnings.push('Foto pertama - tidak ada perbandingan');
                return {
                    isValid: true,
                    errors: [],
                    warnings: warnings
                };
            }

            // Bandingkan dengan foto sebelumnya
            const comparison = compareFaceDescriptors(newDescriptor, previousFaceDescriptors);

            if (!comparison.isConsistent) {
                errors.push({
                    type: 'consistency',
                    message: comparison.message,
                    distance: comparison.maxDistance
                });
            } else {
                // Jika konsisten, simpan descriptor untuk perbandingan berikutnya
                previousFaceDescriptors.push(newDescriptor);
                warnings.push(comparison.message);
            }

            return {
                isValid: errors.length === 0,
                errors: errors,
                warnings: warnings
            };
        }

        // Fungsi untuk menampilkan quality overlay
        function showQualityOverlay(qualityCheck, imageUri) {
            const overlay = document.getElementById('qualityOverlay');
            const errorsContainer = document.getElementById('qualityErrors');
            const scoresContainer = document.getElementById('qualityScoresList');

            // Simpan data untuk retry/skip
            currentRejectedImageUri = imageUri;
            currentRejectedImageScores = qualityCheck.scores;

            // Sembunyikan loading
            document.getElementById('loadingOverlay').classList.remove('active');
            document.getElementById('captureCounter').classList.remove('active');

            // Sembunyikan guide text saat quality overlay muncul
            const guideText = document.getElementById('guideText');
            if (guideText) {
                guideText.style.display = 'none';
            }

            // Tampilkan error messages
            errorsContainer.innerHTML = '';
            qualityCheck.errors.forEach(error => {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'quality-error-item';
                const icon = document.createElement('i');
                icon.className = 'ti ti-alert-circle me-2';
                errorDiv.appendChild(icon);
                errorDiv.appendChild(document.createTextNode(error.message));
                errorsContainer.appendChild(errorDiv);
            });

            // Tampilkan quality scores
            scoresContainer.innerHTML = '';
            if (qualityCheck.scores) {
                const scores = qualityCheck.scores;

                // Blur score
                if (scores.blur !== undefined) {
                    const blurItem = document.createElement('div');
                    blurItem.className = 'quality-score-item';
                    const blurStatus = scores.blur >= 70 ? 'good' : 'bad';

                    const labelDiv = document.createElement('div');
                    labelDiv.className = 'quality-score-label';
                    const icon1 = document.createElement('i');
                    icon1.className = 'ti ti-focus';
                    labelDiv.appendChild(icon1);
                    const span1 = document.createElement('span');
                    span1.textContent = 'Ketajaman (Blur)';
                    labelDiv.appendChild(span1);

                    const valueDiv = document.createElement('div');
                    valueDiv.className = 'quality-score-value ' + blurStatus;
                    valueDiv.textContent = scores.blur.toFixed(1) + ' / 70';

                    blurItem.appendChild(labelDiv);
                    blurItem.appendChild(valueDiv);
                    scoresContainer.appendChild(blurItem);
                }

                // Brightness score
                if (scores.brightness !== undefined) {
                    const brightnessItem = document.createElement('div');
                    brightnessItem.className = 'quality-score-item';
                    let brightnessStatus = 'good';
                    if (scores.brightness < 80) brightnessStatus = 'bad';
                    else if (scores.brightness > 220) brightnessStatus = 'bad';
                    else if (scores.brightness < 100 || scores.brightness > 200) brightnessStatus = 'warning';

                    const labelDiv = document.createElement('div');
                    labelDiv.className = 'quality-score-label';
                    const icon2 = document.createElement('i');
                    icon2.className = 'ti ti-brightness';
                    labelDiv.appendChild(icon2);
                    const span2 = document.createElement('span');
                    span2.textContent = 'Pencahayaan';
                    labelDiv.appendChild(span2);

                    const valueDiv = document.createElement('div');
                    valueDiv.className = 'quality-score-value ' + brightnessStatus;
                    valueDiv.textContent = scores.brightness.toFixed(1) + ' / 80-220';

                    brightnessItem.appendChild(labelDiv);
                    brightnessItem.appendChild(valueDiv);
                    scoresContainer.appendChild(brightnessItem);
                }

                // Sharpness score
                if (scores.sharpness !== undefined) {
                    const sharpnessItem = document.createElement('div');
                    sharpnessItem.className = 'quality-score-item';
                    const sharpnessStatus = scores.sharpness >= 10 ? 'good' : 'bad';

                    const labelDiv = document.createElement('div');
                    labelDiv.className = 'quality-score-label';
                    const icon3 = document.createElement('i');
                    icon3.className = 'ti ti-focus-2';
                    labelDiv.appendChild(icon3);
                    const span3 = document.createElement('span');
                    span3.textContent = 'Ketajaman Detail';
                    labelDiv.appendChild(span3);

                    const valueDiv = document.createElement('div');
                    valueDiv.className = 'quality-score-value ' + sharpnessStatus;
                    valueDiv.textContent = scores.sharpness.toFixed(1) + ' / 10';

                    sharpnessItem.appendChild(labelDiv);
                    sharpnessItem.appendChild(valueDiv);
                    scoresContainer.appendChild(sharpnessItem);
                }
            }

            // Tampilkan overlay
            overlay.classList.add('active');
        }

        // Fungsi helper untuk menyimpan foto setelah validasi
        async function proceedWithSave(uri, qualityScores, skipConsistencyCheck = false) {
            const captureText = document.getElementById('captureText');
            captureText.textContent = `Memproses foto ${capturedImages.length + 1}/${TOTAL_IMAGES}...`;

            // Jika skip consistency check (user pilih "Tetap Simpan"), jangan simpan descriptor
            // karena wajah tidak konsisten dan akan merusak validasi berikutnya
            // Note: Jika valid, descriptor sudah disimpan di validateFaceConsistency
            if (skipConsistencyCheck) {
                console.log('Skipping descriptor save (user chose to skip validation)');
            }

            // Crop wajah dari gambar (Phase 3 - Preprocessing)
            let finalImageUri = uri;
            try {
                captureText.textContent = `Memotong area wajah ${capturedImages.length + 1}/${TOTAL_IMAGES}...`;
                const faceBox = await getFaceBoxFromImage(uri);

                if (faceBox) {
                    // Crop wajah dengan padding 20%
                    finalImageUri = await cropFaceFromImage(uri, faceBox, 20, 224);
                    console.log('Face cropped successfully');
                } else {
                    console.warn('Tidak bisa detect wajah untuk crop, menggunakan gambar asli');
                }
            } catch (error) {
                console.error('Error cropping face:', error);
                // Jika error, gunakan gambar asli
                finalImageUri = uri;
            }

            captureText.textContent = `Foto ${capturedImages.length + 1}/${TOTAL_IMAGES} berhasil!`;

            capturedImages.push({
                direction: DIRECTIONS[currentDirectionIndex].key,
                image: finalImageUri, // Simpan cropped image
                quality: qualityScores // Simpan quality scores untuk reference
            });
            currentImageInDirection++;

            if (currentImageInDirection >= IMAGES_PER_DIRECTION) {
                currentDirectionIndex++;
                currentImageInDirection = 0;
            }
            showDirectionInstruction();

            if (capturedImages.length >= TOTAL_IMAGES) {
                sendImagesToBackend();
                stopMultiCapture();
            } else {
                // Update counter untuk foto berikutnya
                setTimeout(() => {
                    const captureCounter = document.getElementById('captureCounter');
                    const loadingOverlay = document.getElementById('loadingOverlay');
                    captureCounter.classList.remove('active');
                    loadingOverlay.classList.remove('active');
                }, 800);
            }
        }

        function sendImagesToBackend() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            const captureCounter = document.getElementById('captureCounter');
            loadingOverlay.classList.add('active');
            captureCounter.classList.add('active');
            document.getElementById('captureText').textContent = 'Menyimpan gambar ke server...';

            $.ajax({
                type: 'POST',
                url: "{{ route('facerecognition.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: "{{ $nik }}",
                    images: JSON.stringify(capturedImages),
                },
                success: function(data) {
                    loadingOverlay.classList.remove('active');
                    captureCounter.classList.remove('active');

                    swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '5 Gambar wajah berhasil disimpan',
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(function() {
                        window.location.href = "{{ route('dashboard.index') }}";
                    });
                },
                error: function(xhr) {
                    loadingOverlay.classList.remove('active');
                    captureCounter.classList.remove('active');

                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menyimpan gambar',
                        showConfirmButton: true,
                    });
                }
            });
        }

        // ============================================
        // FACE DETECTION
        // ============================================
        async function detectFace() {
            if (!isModelsLoaded) {
                return;
            }

            try {
                const video = document.querySelector('.webcam-capture video');
                if (!video) {
                    return false;
                }

                // Pastikan video sudah ready
                if (video.readyState !== video.HAVE_ENOUGH_DATA) {
                    return false;
                }

                // Optimasi untuk Android: gunakan TinyFaceDetector (lebih ringan)
                const isAndroid = /Android/i.test(navigator.userAgent);

                let detection;
                if (isAndroid) {
                    // TinyFaceDetector dengan inputSize 224 (keseimbangan performa dan akurasi)
                    // inputSize 224 lebih akurat dari 160 tapi masih ringan
                    try {
                        detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                            inputSize: 224,
                            scoreThreshold: 0.3 // Threshold lebih rendah untuk deteksi lebih sensitif
                        })).withFaceLandmarks();

                        // Jika tidak terdeteksi dengan TinyFaceDetector, coba dengan inputSize lebih besar
                        if (!detection) {
                            detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                                inputSize: 320,
                                scoreThreshold: 0.25 // Threshold lebih rendah lagi
                            })).withFaceLandmarks();
                        }
                    } catch (error) {
                        console.warn('TinyFaceDetector error, trying SSD Mobilenet fallback:', error);
                        // Fallback ke SSD Mobilenet jika TinyFaceDetector error
                        detection = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options({
                            minConfidence: 0.4,
                            maxResults: 1
                        })).withFaceLandmarks();
                    }
                } else {
                    // SSD Mobilenet untuk Desktop/iOS (lebih akurat)
                    detection = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options({
                        minConfidence: 0.5,
                        maxResults: 1
                    })).withFaceLandmarks();
                }

                if (detection) {
                    const box = detection.detection.box;

                    // Update video dimensions jika berubah
                    if (video.videoWidth > 0 && video.videoHeight > 0) {
                        // Pastikan update dimensi dengan benar
                        const newWidth = video.videoWidth;
                        const newHeight = video.videoHeight;

                        if (newWidth !== videoWidth || newHeight !== videoHeight) {
                            videoWidth = newWidth;
                            videoHeight = newHeight;
                            console.log('Video dimensions updated:', `${videoWidth}x${videoHeight}`, 'Aspect ratio:', (videoWidth / videoHeight)
                                .toFixed(2));
                        }
                    }

                    const centerX = videoWidth / 2;
                    const centerY = videoHeight * 0.475; // 47.5% untuk konsistensi dengan guide box yang di 45%
                    const faceCenterX = box.x + box.width / 2;
                    const faceCenterY = box.y + box.height / 2;

                    // Gunakan threshold dinamis berdasarkan resolusi video
                    const thresholds = getFaceSizeThresholds();

                    // Threshold posisi dinamis - lebih longgar untuk Android
                    const isAndroid = /Android/i.test(navigator.userAgent);
                    const positionThresholdPercent = isAndroid ? 0.12 : 0.08; // 12% untuk Android, 8% untuk Desktop/iOS
                    const positionThresholdX = videoWidth * positionThresholdPercent;
                    const positionThresholdY = videoHeight * positionThresholdPercent;

                    // Hitung selisih posisi untuk debugging
                    const diffX = Math.abs(faceCenterX - centerX);
                    const diffY = Math.abs(faceCenterY - centerY);
                    const diffXPercent = ((diffX / videoWidth) * 100).toFixed(1);
                    const diffYPercent = ((diffY / videoHeight) * 100).toFixed(1);

                    const isInPosition =
                        diffX < positionThresholdX &&
                        diffY < positionThresholdY &&
                        box.width >= thresholds.minWidth && box.width <= thresholds.maxWidth &&
                        box.height >= thresholds.minHeight && box.height <= thresholds.maxHeight;

                    // Debug logging (bisa dihapus nanti)
                    if (!isInPosition && isAndroid) {
                        console.log('Position check:', {
                            center: `(${centerX.toFixed(0)}, ${centerY.toFixed(0)})`,
                            faceCenter: `(${faceCenterX.toFixed(0)}, ${faceCenterY.toFixed(0)})`,
                            diff: `(${diffX.toFixed(0)}, ${diffY.toFixed(0)})`,
                            diffPercent: `(${diffXPercent}%, ${diffYPercent}%)`,
                            threshold: `(${positionThresholdX.toFixed(0)}, ${positionThresholdY.toFixed(0)})`,
                            thresholdPercent: `${(positionThresholdPercent * 100).toFixed(0)}%`,
                            passed: diffX < positionThresholdX && diffY < positionThresholdY
                        });
                    }

                    const statusIndicator = document.getElementById('statusIndicator');
                    const guideText = document.getElementById('guideText');
                    const faceGuide = document.getElementById('faceGuide');
                    const progressContainer = document.getElementById('progressContainer');
                    const progressFill = document.getElementById('progressFill');
                    const progressText = document.getElementById('progressText');

                    if (isInPosition) {
                        consecutiveGoodPositions++;
                        statusIndicator.classList.add('ready');
                        faceGuide.classList.add('ready');
                        progressContainer.classList.add('active');

                        const progress = Math.min(100, (consecutiveGoodPositions / REQUIRED_CONSECUTIVE_POSITIONS) * 100);
                        progressFill.style.width = progress + '%';

                        if (consecutiveGoodPositions >= REQUIRED_CONSECUTIVE_POSITIONS) {
                            guideText.textContent = '✅ Posisi wajah sudah tepat, mengambil foto...';
                            guideText.classList.add('ready');
                            progressText.textContent = 'Stabil! Mengambil foto...';
                            isFaceDetected = true;

                            if (consecutiveGoodPositions >= REQUIRED_CONSECUTIVE_POSITIONS) {
                                if (!autoCaptureTimeout) {
                                    autoCaptureTimeout = setTimeout(() => {
                                        capturePhoto();
                                        autoCaptureTimeout = null;
                                    }, 1000);
                                }
                            }
                        } else {
                            guideText.textContent = `Posisi wajah sudah tepat, tunggu sebentar... (${Math.round(progress)}%)`;
                            guideText.classList.remove('ready');
                            progressText.textContent = `Stabilitas: ${Math.round(progress)}%`;
                        }
                    } else {
                        consecutiveGoodPositions = 0;
                        statusIndicator.classList.remove('ready');
                        faceGuide.classList.remove('ready');
                        progressContainer.classList.remove('active');
                        progressFill.style.width = '0%';

                        // Gunakan threshold dinamis untuk pesan error
                        const thresholds = getFaceSizeThresholds();
                        const isAndroid = /Android/i.test(navigator.userAgent);
                        const positionThresholdPercent = isAndroid ? 0.12 : 0.08; // 12% untuk Android, 8% untuk Desktop/iOS
                        const positionThresholdX = videoWidth * positionThresholdPercent;
                        const positionThresholdY = videoHeight * positionThresholdPercent;

                        let guideMessage = 'Posisikan wajah Anda di dalam kotak panduan hijau';

                        // Debug info (bisa dihapus nanti)
                        const faceSizePercent = ((box.width / videoWidth) * 100).toFixed(1);
                        const diffX = Math.abs(faceCenterX - centerX);
                        const diffY = Math.abs(faceCenterY - centerY);
                        const diffXPercent = ((diffX / videoWidth) * 100).toFixed(1);
                        const diffYPercent = ((diffY / videoHeight) * 100).toFixed(1);

                        // Prioritas: cek posisi dulu, baru ukuran
                        // Tapi hanya tampilkan pesan geser jika benar-benar jauh dari center
                        if (diffX > positionThresholdX) {
                            guideMessage = faceCenterX < centerX ? '← Geser ke kanan' : 'Geser ke kiri →';
                        } else if (diffY > positionThresholdY) {
                            guideMessage = faceCenterY < centerY ? '↑ Geser ke bawah' : 'Geser ke atas ↓';
                        } else if (box.width < thresholds.minWidth) {
                            guideMessage =
                                `⚠️ Mendekatlah ke kamera (wajah ${faceSizePercent}%, perlu minimal ${(thresholds.minWidth/videoWidth*100).toFixed(1)}%)`;
                        } else if (box.width > thresholds.maxWidth) {
                            guideMessage =
                                `⚠️ Menjauhlah dari kamera (wajah ${faceSizePercent}%, maksimal ${(thresholds.maxWidth/videoWidth*100).toFixed(1)}%)`;
                        } else if (box.height < thresholds.minHeight) {
                            guideMessage = '⚠️ Posisikan wajah lebih vertikal';
                        } else if (box.height > thresholds.maxHeight) {
                            guideMessage = '⚠️ Posisikan wajah lebih vertikal';
                        }

                        if (guideText) {
                            guideText.textContent = guideMessage;
                            guideText.classList.remove('ready');
                        }
                        isFaceDetected = false;
                    }
                } else {
                    consecutiveGoodPositions = 0;
                    const statusIndicator = document.getElementById('statusIndicator');
                    const guideText = document.getElementById('guideText');
                    const faceGuide = document.getElementById('faceGuide');
                    const progressContainer = document.getElementById('progressContainer');

                    statusIndicator.classList.remove('ready');
                    faceGuide.classList.remove('ready');
                    progressContainer.classList.remove('active');

                    if (guideText) {
                        guideText.textContent = '❌ Wajah tidak terdeteksi. Pastikan wajah menghadap kamera';
                        guideText.classList.remove('ready');
                    }
                    isFaceDetected = false;
                }
            } catch (error) {
                console.error('Error detecting face:', error);
                const guideText = document.getElementById('guideText');
                if (guideText) {
                    guideText.textContent = '❌ Terjadi kesalahan dalam deteksi wajah';
                    guideText.classList.remove('ready');
                }
            }
        }

        // ============================================
        // INITIALIZATION
        // ============================================
        let isFaceDetected = false;
        let isProcessing = false;
        let autoCaptureTimeout = null;
        let consecutiveGoodPositions = 0;
        const REQUIRED_CONSECUTIVE_POSITIONS = 10;
        let lastCaptureTime = 0;
        const MIN_CAPTURE_INTERVAL = 2000;
        let isModelsLoaded = false;

        // Dynamic threshold berdasarkan resolusi video
        let videoWidth = 1280; // Default, akan diupdate saat video ready
        let videoHeight = 720; // Default, akan diupdate saat video ready

        // Fungsi untuk mendapatkan threshold dinamis
        function getFaceSizeThresholds() {
            // Threshold berdasarkan persentase dari resolusi video
            // Lebih fleksibel untuk berbagai aspect ratio
            // Diperlonggar lebih banyak untuk Android yang mungkin punya aspect ratio berbeda

            // Hitung aspect ratio
            const aspectRatio = videoWidth / videoHeight;
            const isAndroid = /Android/i.test(navigator.userAgent);

            // Untuk Android dengan aspect ratio berbeda (biasanya lebih tinggi/portrait)
            // atau untuk resolusi rendah, threshold lebih longgar
            let minWidthPercent, maxWidthPercent, minHeightPercent, maxHeightPercent;

            if (isAndroid) {
                // Android: threshold lebih longgar karena aspect ratio bisa berbeda
                minWidthPercent = 0.08; // 8% dari lebar video (minimum) - lebih longgar
                maxWidthPercent = 0.50; // 50% dari lebar video (maximum) - jauh lebih longgar
                minHeightPercent = 0.10; // 10% dari tinggi video (minimum) - lebih longgar
                maxHeightPercent = 0.60; // 60% dari tinggi video (maximum) - jauh lebih longgar
            } else {
                // Desktop/iOS: threshold normal
                minWidthPercent = 0.10; // 10% dari lebar video (minimum)
                maxWidthPercent = 0.40; // 40% dari lebar video (maximum) - lebih longgar dari sebelumnya
                minHeightPercent = 0.12; // 12% dari tinggi video (minimum)
                maxHeightPercent = 0.50; // 50% dari tinggi video (maximum) - lebih longgar
            }

            const thresholds = {
                minWidth: Math.floor(videoWidth * minWidthPercent),
                maxWidth: Math.floor(videoWidth * maxWidthPercent),
                minHeight: Math.floor(videoHeight * minHeightPercent),
                maxHeight: Math.floor(videoHeight * maxHeightPercent)
            };

            // Debug logging
            console.log('Face size thresholds:', {
                videoSize: `${videoWidth}x${videoHeight}`,
                aspectRatio: aspectRatio.toFixed(2),
                isAndroid: isAndroid,
                thresholds: thresholds,
                minWidthPercent: (minWidthPercent * 100).toFixed(1) + '%',
                maxWidthPercent: (maxWidthPercent * 100).toFixed(1) + '%'
            });

            return thresholds;
        }

        // Variabel untuk tracking retry initialize
        let initRetryCount = 0;
        const MAX_INIT_RETRIES = 5;

        async function initializeFaceRecognition() {
            try {
                // Pastikan DOM sudah ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(initializeFaceRecognition, 200);
                    });
                    return;
                }

                // Pastikan elemen webcam-capture ada
                const webcamElement = document.querySelector('.webcam-capture') || document.getElementById('webcamCapture');
                if (!webcamElement) {
                    initRetryCount++;
                    if (initRetryCount < MAX_INIT_RETRIES) {
                        console.warn(`Webcam element not found, retrying... (${initRetryCount}/${MAX_INIT_RETRIES})`);
                        setTimeout(initializeFaceRecognition, 1000);
                    } else {
                        console.error('Webcam element not found after maximum retries. Please check if the element exists in the DOM.');
                        swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Elemen kamera tidak ditemukan. Silakan refresh halaman.',
                            showConfirmButton: true
                        });
                    }
                    return;
                }

                // Reset retry count jika elemen ditemukan
                initRetryCount = 0;
                console.log('Webcam element found, initializing...');

                await loadFaceApiScript();
                isModelsLoaded = await loadFaceApiModels();

                if (isModelsLoaded) {
                    // Tunggu sebentar sebelum start video untuk memastikan elemen siap
                    setTimeout(() => {
                        startVideo();
                        // Optimasi untuk Android: gunakan requestAnimationFrame dan throttle
                        const isAndroid = /Android/i.test(navigator.userAgent);
                        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                        // Interval deteksi lebih lama untuk Android
                        const DETECTION_INTERVAL = isAndroid ? 300 : isMobile ? 200 : 150; // 300ms untuk Android = ~3 FPS

                        let lastDetectionTime = 0;
                        let detectionFrameId = null;
                        let isDetecting = false; // Flag untuk mencegah overlapping detection

                        function scheduleDetection() {
                            if (detectionFrameId) {
                                cancelAnimationFrame(detectionFrameId);
                            }
                            detectionFrameId = requestAnimationFrame(() => {
                                const now = Date.now();
                                if (now - lastDetectionTime >= DETECTION_INTERVAL && !isDetecting) {
                                    lastDetectionTime = now;
                                    isDetecting = true;
                                    detectFace().then(() => {
                                        isDetecting = false;
                                    }).catch(err => {
                                        console.error('Error in detectFace:', err);
                                        isDetecting = false;
                                    });
                                }
                                scheduleDetection();
                            });
                        }
                        scheduleDetection();
                    }, 500); // Increase delay untuk memastikan elemen siap

                    $("#btnMulaiRekam").click(function() {
                        $("#btnMulaiRekam").prop("disabled", true);
                        startMultiCapture();
                    });
                } else {
                    swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat model pengenalan wajah. Silakan muat ulang halaman.',
                        showConfirmButton: true
                    });
                }
            } catch (error) {
                console.error('Error initializing face recognition:', error);
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menginisialisasi pengenalan wajah.',
                    showConfirmButton: true
                });
            }
        }

        // ============================================
        // EVENT HANDLERS FOR QUALITY OVERLAY
        // ============================================

        // Event handlers untuk tombol quality overlay
        function setupQualityOverlayHandlers() {
            const btnRetry = document.getElementById('qualityBtnRetry');
            const btnSkip = document.getElementById('qualityBtnSkip');
            const qualityOverlay = document.getElementById('qualityOverlay');

            if (btnRetry) {
                btnRetry.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Retry button clicked');
                    if (qualityOverlay) {
                        qualityOverlay.classList.remove('active');
                    }
                    // Tampilkan kembali guide text
                    const guideText = document.getElementById('guideText');
                    if (guideText) {
                        guideText.style.display = '';
                    }
                    // Reset variabel - user akan otomatis retry saat posisi wajah tepat lagi
                    currentRejectedImageUri = null;
                    currentRejectedImageScores = null;
                });
            }

            if (btnSkip) {
                btnSkip.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Skip button clicked', currentRejectedImageUri);
                    if (qualityOverlay) {
                        qualityOverlay.classList.remove('active');
                    }
                    // Tampilkan kembali guide text
                    const guideText = document.getElementById('guideText');
                    if (guideText) {
                        guideText.style.display = '';
                    }
                    // Simpan foto meskipun kualitas/konsistensi tidak memenuhi
                    // skipConsistencyCheck = true karena user memilih skip
                    if (currentRejectedImageUri) {
                        console.log('Proceeding with save (skipping validation)...');
                        proceedWithSave(currentRejectedImageUri, currentRejectedImageScores, true);
                        currentRejectedImageUri = null;
                        currentRejectedImageScores = null;
                    } else {
                        console.warn('No rejected image to save');
                    }
                });
            }
        }

        // Initialize
        // Setup event handlers saat DOM ready
        function initAll() {
            // Pastikan bottom navigation tetap terlihat
            const bottomNav = document.querySelector('.appBottomMenu, .bottom-nav, nav.bottom-nav, footer, .footer, .app-footer');
            if (bottomNav) {
                bottomNav.style.display = '';
                bottomNav.style.visibility = '';
                bottomNav.style.opacity = '';
                bottomNav.style.height = '';
                bottomNav.style.zIndex = '10000';
                bottomNav.style.position = 'fixed';
            }

            // Pastikan elemen webcam-capture sudah ada di DOM
            const webcamContainer = document.querySelector('.webcam-container');
            const webcamCapture = document.querySelector('.webcam-capture') || document.getElementById('webcamCapture');

            if (!webcamContainer || !webcamCapture) {
                // Retry counter untuk initAll
                if (typeof initAll.retryCount === 'undefined') {
                    initAll.retryCount = 0;
                }
                initAll.retryCount++;

                if (initAll.retryCount < 5) {
                    console.warn(`Webcam elements not found, retrying... (${initAll.retryCount}/5)`);
                    console.log('Container:', webcamContainer);
                    console.log('Capture:', webcamCapture);
                    setTimeout(initAll, 1000);
                } else {
                    console.error('Webcam elements not found after 5 retries. Please check the HTML structure.');
                }
                return;
            }

            // Reset retry count jika elemen ditemukan
            initAll.retryCount = 0;

            console.log('All elements found, initializing...');

            // Setup quality overlay handlers
            setupQualityOverlayHandlers();

            // Initialize face recognition
            initializeFaceRecognition();
            updateStepIndicator(1);
        }

        // Pastikan DOM ready sebelum initialize
        function waitForDOM() {
            // Cek langsung dulu tanpa interval
            const webcamContainer = document.querySelector('.webcam-container');
            const webcamCapture = document.querySelector('.webcam-capture') || document.getElementById('webcamCapture');

            if (webcamContainer && webcamCapture) {
                console.log('Webcam elements found immediately, initializing...');
                // Tunggu sebentar untuk memastikan semua style ter-apply
                setTimeout(initAll, 100);
                return;
            }

            // Jika tidak ditemukan, gunakan MutationObserver untuk mendeteksi saat elemen ditambahkan
            const observer = new MutationObserver((mutations, obs) => {
                const container = document.querySelector('.webcam-container');
                const capture = document.querySelector('.webcam-capture') || document.getElementById('webcamCapture');

                if (container && capture) {
                    obs.disconnect();
                    console.log('Webcam elements found via MutationObserver, initializing...');
                    setTimeout(initAll, 100);
                }
            });

            // Observe perubahan di body
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            // Fallback: timeout setelah 3 detik
            setTimeout(() => {
                observer.disconnect();
                const container = document.querySelector('.webcam-container');
                const capture = document.querySelector('.webcam-capture') || document.getElementById('webcamCapture');

                if (container && capture) {
                    console.log('Webcam elements found after timeout, initializing...');
                    initAll();
                } else {
                    console.error('Webcam elements not found after 3 seconds. Please check the HTML structure.');
                    console.log('Available elements:', {
                        container: container,
                        capture: capture,
                        bodyChildren: document.body.children.length
                    });
                }
            }, 3000);
        }

        // Start initialization setelah DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(waitForDOM, 50);
            });
        } else {
            // DOM sudah ready, tunggu sebentar untuk memastikan semua elemen ter-render
            setTimeout(waitForDOM, 50);
        }
    </script>
@endsection
