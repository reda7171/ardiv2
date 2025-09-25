<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Houzez_Agency_Name extends Widget_Base {
	use Houzez_Style_Traits;
	use \HouzezThemeFunctionality\Elementor\Traits\Houzez_Preview_Query;

	public function get_name() {
		return 'houzez-agency-name';
	}

	public function get_title() {
		return __( 'Agency Name', 'houzez-theme-functionality' );
	}

	public function get_icon() {
		return 'houzez-element-icon houzez-agency eicon-post-title';
	}

	public function get_categories() {
		if(get_post_type() === 'fts_builder' && htb_get_template_type(get_the_id()) === 'single-agency')  {
            return ['houzez-single-agency-builder']; 
        }

		return [ 'houzez-single-agency' ];
	}

	public function get_keywords() {
		return [ 'houzez', 'agency name', 'title', 'heading', 'agency' ];
	}

	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Agency Name', 'houzez-theme-functionality' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'header_size',
			[
				'label' => esc_html__( 'HTML Tag', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'hide_verified_text',
			[
				'label' => esc_html__( 'Hide "Verified" Text', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
				'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
				'return_value' => 'yes',
				'default' => '',
				'description' => esc_html__( 'Show only the verification icon without text', 'houzez-theme-functionality' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Content', 'houzez-theme-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->Houzez_Widget_Heading_Style();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'agency_name_typography',
				'label' => esc_html__( 'Agency Name Typography', 'houzez-theme-functionality' ),
				'selector' => '{{WRAPPER}} .elementor-heading-title a, {{WRAPPER}} .elementor-heading-title span[itemprop="name"]',
			]
		);

		$this->start_controls_tabs( 'agency_name_colors' );

		$this->start_controls_tab(
			'agency_name_normal',
			[
				'label' => esc_html__( 'Normal', 'houzez-theme-functionality' ),
			]
		);

		$this->add_control(
			'agency_name_color',
			[
				'label' => esc_html__( 'Agency Name Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-heading-title span[itemprop="name"]' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'agency_name_hover',
			[
				'label' => esc_html__( 'Hover', 'houzez-theme-functionality' ),
			]
		);

		$this->add_control(
			'agency_name_hover_color',
			[
				'label' => esc_html__( 'Agency Name Hover Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'agency_name_margin',
			[
				'label' => esc_html__( 'Margin', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Verification Badge Styling
		$this->start_controls_section(
			'section_verification_badge_style',
			[
				'label' => esc_html__( 'Verification Badge', 'houzez-theme-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'verification_badge_typography',
				'label' => esc_html__( 'Typography', 'houzez-theme-functionality' ),
				'selector' => '{{WRAPPER}} .agent-verified-badge, {{WRAPPER}} .agent-verified-icon',
			]
		);

		$this->add_control(
			'verification_badge_color',
			[
				'label' => esc_html__( 'Text Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-verified-badge' => 'color: {{VALUE}};',
					'{{WRAPPER}} .agent-verified-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'verification_badge_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-verified-badge' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .agent-verified-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'verification_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .agent-verified-badge i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .agent-verified-icon i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'verification_badge_padding',
			[
				'label' => esc_html__( 'Padding', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 4,
					'right' => 4,
					'bottom' => 5,
					'left' => 4,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .agent-verified-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .agent-verified-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'verification_badge_margin',
			[
				'label' => esc_html__( 'Margin', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .agent-verified-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .agent-verified-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'verification_badge_border',
				'label' => esc_html__( 'Border', 'houzez-theme-functionality' ),
				'selector' => '{{WRAPPER}} .agent-verified-badge, {{WRAPPER}} .agent-verified-icon',
			]
		);

		$this->add_responsive_control(
			'verification_badge_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .agent-verified-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .agent-verified-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'verification_badge_shadow',
				'label' => esc_html__( 'Box Shadow', 'houzez-theme-functionality' ),
				'selector' => '{{WRAPPER}} .agent-verified-badge, {{WRAPPER}} .agent-verified-icon',
			]
		);

		$this->end_controls_section();
	}

		protected function render() {
		$this->single_agency_preview_query(); // Only for preview

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'title', 'class', 'mb-0 d-flex align-items-center gap-2' );
		$this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', 'elementor-size-' . $settings['size'] );
		} else {
			$this->add_render_attribute( 'title', 'class', 'elementor-size-default' );
		}

		$permalink = get_permalink();
		$title = get_the_title();

		// Vérification agence
		$is_verified = get_post_meta( get_the_ID(), 'fave_agency_verified', true );

		// Récupérer l’ID de l’agence et son package
		$agent_agency_id = houzez_get_agent_agency_id( get_the_ID() );
		$package_id      = houzez_get_user_package_id( $agent_agency_id );

		$verification_badge = '';
		if ( $is_verified && intval($package_id) === 1240 ) {
			if ( $settings['hide_verified_text'] === 'yes' ) {
				$verification_badge = '<span class="badge btn-secondary agent-verified-badge"><i class="houzez-icon icon-check-circle-1"></i></span>';
			} else {
				$verification_badge = '<span class="badge btn-secondary agent-verified-badge"><i class="houzez-icon icon-check-circle-1 me-1"></i> ' . esc_html__( 'Verified', 'houzez' ) . '</span>';
			}
		}

		// Créer le lien ou simple texte
		if ( ! empty( $permalink ) && $settings['link'] === 'yes' ) {
			$agency_link = sprintf( '<a href="%1$s" itemprop="name">%2$s</a>', esc_url( $permalink ), esc_html( $title ) );
		} else {
			$agency_link = sprintf( '<span itemprop="name">%s</span>', esc_html( $title ) );
		}

		// Combiner nom + badge
		$content = $agency_link . ' ' . $verification_badge;

		$title_html = sprintf(
			'<%1$s %2$s>%3$s</%1$s>',
			Utils::validate_html_tag( $settings['header_size'] ),
			$this->get_render_attribute_string( 'title' ),
			$content
		);

		// Affichage final
		echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$this->reset_preview_query(); // Only for preview
	}

}
Plugin::instance()->widgets_manager->register( new Houzez_Agency_Name );