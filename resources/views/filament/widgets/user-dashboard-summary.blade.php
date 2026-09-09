<style>
    .user-dashboard-summary-card {
        overflow: hidden;
        border-radius: 28px;
        border: 1px solid rgb(226 232 240);
        background: #ffffff;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.08);
    }

    .user-dashboard-summary-card * {
        box-sizing: border-box;
    }

    .user-dashboard-summary-card img {
        display: block;
        max-width: 100%;
    }

    .user-dashboard-summary-card .user-dashboard-summary-topbar {
        background: #3E271A;
        padding: 1.25rem 1.5rem;
    }

    .user-dashboard-summary-card .user-dashboard-summary-layout {
        display: grid;
        gap: 1.25rem;
        padding: 1.25rem;
    }

    @media (min-width: 640px) {
        .user-dashboard-summary-card .user-dashboard-summary-layout {
            grid-template-columns: minmax(0, 1fr) minmax(240px, 280px);
            align-items: start;
            padding: 1.5rem;
        }
    }

    .user-dashboard-summary-card .user-dashboard-summary-identity {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
        text-align: center;
    }

    @media (min-width: 640px) {
        .user-dashboard-summary-card .user-dashboard-summary-identity {
            align-items: flex-start;
            text-align: left;
        }
    }

    .user-dashboard-summary-card .user-dashboard-summary-avatar {
        width: 11rem;
        height: 11rem;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 1.25rem;
        background: #F5F5DC;
        border: 4px solid #F5F5DC;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 800;
        color: #3E271A;
    }

    .user-dashboard-summary-card .user-dashboard-summary-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-dashboard-summary-card .user-dashboard-summary-name {
        margin: 0;
        font-size: 1.25rem;
        line-height: 1.3;
        font-weight: 800;
        color: #0f172a;
        word-break: break-word;
    }

    .user-dashboard-summary-card .user-dashboard-summary-email {
        margin: 0.35rem 0 0;
        font-size: 0.875rem;
        color: #64748b;
        word-break: break-word;
    }

    .user-dashboard-summary-card .user-dashboard-summary-qr {
        width: 100%;
        max-width: 250px;
        margin: 0 auto;
        border-radius: 1.5rem;
        border: 1px solid rgb(226 232 240);
        background: #f8fafc;
        padding: 0.75rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .user-dashboard-summary-card .user-dashboard-summary-qr img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    .user-dashboard-summary-card .user-dashboard-summary-meta {
        display: grid;
        gap: 1rem;
        margin: 0;
    }

    @media (min-width: 640px) {
        .user-dashboard-summary-card .user-dashboard-summary-meta {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .user-dashboard-summary-card .user-dashboard-summary-meta-item {
        min-width: 0;
    }

    .user-dashboard-summary-card .user-dashboard-summary-meta-item dt {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #94a3b8;
    }

    .user-dashboard-summary-card .user-dashboard-summary-meta-item dd {
        margin: 0.35rem 0 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
    }
</style>

<div class="user-dashboard-summary-card">
    <div class="user-dashboard-summary-topbar"></div>

    <div class="user-dashboard-summary-layout">
        <section class="user-dashboard-summary-identity">
            <div class="user-dashboard-summary-avatar">
                @if (filled($user?->avatar_path))
                    <img src="{{ $user->avatar_url }}" alt="Foto profil {{ $user->name }}" />
                @else
                    {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                @endif
            </div>

            <div>
                <h3 class="user-dashboard-summary-name">{{ $user?->name }}</h3>
                <p class="user-dashboard-summary-email">{{ $user?->email }}</p>
            </div>
        </section>

        <section>
            @if (filled($user?->qr_code_url))
                <div class="user-dashboard-summary-qr">
                    <img src="{{ $user->qr_code_url }}" alt="QR Code profil {{ $user->name }}" />
                </div>
            @endif
        </section>

        <dl class="user-dashboard-summary-meta" style="grid-column: 1 / -1;">
            <div class="user-dashboard-summary-meta-item" style="grid-column: 1 / -1;">
                <dt>Nomor Tanda Anggota</dt>
                <dd>{{ $user?->nta ?: '-' }}</dd>
            </div>

            <div class="user-dashboard-summary-meta-item">
                <dt>Jabatan</dt>
                <dd>{{ $user?->jabatan ?: '-' }}</dd>
            </div>
            <div class="user-dashboard-summary-meta-item">
                <dt>Satuan</dt>
                <dd>{{ $user?->satuan ?: '-' }}</dd>
            </div>
            <div class="user-dashboard-summary-meta-item">
                <dt>Golongan</dt>
                <dd>{{ $user?->golongan ?: '-' }}</dd>
            </div>
            <div class="user-dashboard-summary-meta-item">
                <dt>Tingkatan</dt>
                <dd>{{ $user?->tingkatan ?: '-' }}</dd>
            </div>
        </dl>
    </div>
</div>