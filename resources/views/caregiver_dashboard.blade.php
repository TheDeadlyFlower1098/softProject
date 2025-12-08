@extends('layouts.app')

@section('title', 'Caregiver Home')

@section('content')
    <style>
        .caregiver-wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .caregiver-title {
            align-self: flex-start;
            font-size: 2.5rem;
            font-weight: 600;
            color: #022;
            margin-bottom: 18px;
        }

        .caregiver-layout {
            display: flex;
            gap: 24px;
            width: 100%;
        }

        /* Left main panel (green) */
        .caregiver-board {
            flex: 3;
            background: #b6d7a8;
            border-radius: 10px;
            padding: 24px 28px;
            border: 2px solid #6aa84f;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        .caregiver-table-header {
            margin: 0 auto 40px;
            max-width: 80%;
            padding: 14px 18px;
            background: #c9daf8;
            border-radius: 6px;
            border: 1px solid #6d9eeb;
            text-align: center;
            font-weight: 600;
            color: #000;
        }

        .caregiver-table-header span {
            display: block;
            font-size: 0.98rem;
        }

        .caregiver-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            margin-top: 20px;
        }

        .caregiver-controls button {
            min-width: 110px;
            height: 34px;
            background: #d9ead3;
            border-radius: 4px;
            border: 2px solid #6aa84f;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .caregiver-controls button:hover {
            background: #c9e2be;
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.12);
        }

        /* Right side panel (blue) */
        .caregiver-side-panel {
            flex: 1;
            background: #9fc5e8;
            border-radius: 10px;
            border: 2px solid #6d9eeb;
            padding: 18px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .caregiver-side-panel p {
            color: #022;
            font-size: 0.95rem;
            line-height: 1.5;
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .caregiver-layout {
                flex-direction: column;
            }

            .caregiver-side-panel {
                min-height: 180px;
            }

            .caregiver-table-header {
                max-width: 100%;
            }
        }
    </style>

    <div class="caregiver-wrapper">
        <h1 class="caregiver-title">Caregiver</h1>

        <div class="caregiver-layout">

            {{-- LEFT: main green board --}}
            <section class="caregiver-board">
                <div class="caregiver-table-header">
                    <span>
                        name, date, comment, morning med, afternoon med,
                        night med, breakfast, lunch, dinner
                    </span>
                </div>

                <div class="caregiver-controls">
                    {{-- These are just placeholder boxes / buttons for now --}}
                    <button type="button"></button>
                    <button type="button"></button>
                    <button type="button"></button>
                    <button type="button"></button>
                    <button type="button"></button>
                </div>
            </section>

            {{-- RIGHT: tall blue side panel --}}
            <aside class="caregiver-side-panel">
                <p>
                    Side panel for future caregiver tools, notes,
                    or resident details.
                </p>
            </aside>

        </div>
    </div>
@endsection
