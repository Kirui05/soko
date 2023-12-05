import { memo } from '@wordpress/element';

const School = (props) => {
	const { className, ...otherProps } = props;

	return (
		<svg
			className={className}
			viewBox="0 0 24 24"
			fill="currentColor"
			xmlns="http://www.w3.org/2000/svg"
			{...otherProps}>
			<path d="M0 0h24v24H0V0z" fill="none" />
			<path
				d="M7 12.27v3.72l5 2.73 5-2.73v-3.72L12 15zM5.18 9L12 12.72 18.82 9 12 5.28z"
				opacity=".3"
			/>
			<path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm5 12.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72zm-5-3.27L5.18 9 12 5.28 18.82 9 12 12.72z" />
		</svg>
	);
};

export default memo(School);
