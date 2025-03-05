<?php

class TicketView {
    public function render($tickets) {
        $this->renderHeader();
        $this->renderContent($tickets);
        $this->renderFooter();
    }

    private function renderHeader() {
        include 'header.php';
        echo '<div class="tickets-container">';
        echo '<div class="tickets-header">';
        $this->renderFilters();
        echo '</div>';
    }

    private function renderFilters() {
        ?>
        <h1><i class="fas fa-ticket-alt"></i> Gestion des Tickets</h1>
        <div class="tickets-filters">
            <button class="filter-btn active" data-filter="all">Tous</button>
            <button class="filter-btn" data-filter="pending">En attente</button>
            <button class="filter-btn" data-filter="moderated">Modérés</button>
        </div>
        <?php
    }

    private function renderContent($tickets) {
        foreach ($tickets as $ticket) {
            $comment = new Comment($ticket);
            $this->renderTicketCard($comment);
        }
    }

    private function renderTicketCard(Comment $comment) {
        ?>
        <div class="ticket-card" data-status="<?php echo $comment->getIsModerated() ? 'moderated' : 'pending'; ?>">
            // ...existing code...
        </div>
        <?php
    }

    private function renderFooter() {
        ?>
        </div>
        <?php include 'footer.php'; ?>
        <script>
            // ...existing code...
        </script>
        </body>
        </html>
        <?php
    }
}